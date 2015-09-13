<?php

namespace im\filesystem\components;

use im\base\interfaces\ModelBehaviorInterface;
use yii\base\Behavior;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use Yii;

/**
 * Class FilesBehavior adds possibility to handle file uploads and link them to the model.
 *
 * @property ActiveRecord $owner
 * @package im\filesystem\components
 */
class FilesBehavior extends Behavior implements ModelBehaviorInterface
{
    /**
     * @var StorageConfig[]
     */
    public $attributes = [];

    /**
     * @var string default storage config class
     */
    public $storageConfigClass = 'im\filesystem\components\StorageConfig';

    /**
     * @var UploadedFile[]
     */
    private $_uploadedFiles = [];

    /**
     * @var UploadedFile[]
     */
    private $_relatedFiles = [];

    /**
     * @var FilesystemComponent
     */
    private $_filesystemComponent;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    /**
     * Handles before validate event of owner.
     * Creates instances of uploaded files.
     */
    public function beforeValidate()
    {
        if ($this->hasUploadedFiles()) {
            $this->normalizeAttributes();
            $this->getUploadedFileInstances();
        }
    }

    /**
     * Handles after update event of owner.
     * Saves uploaded files to filesystem.
     */
    public function afterSave()
    {
        $this->saveUploadedFiles();
        $this->linkRelatedFiles();
    }

    /**
     * Handles before delete event of owner.
     */
    public function beforeDelete()
    {
        $this->deleteRelatedFiles();
    }

    /**
     * @inheritdoc
     */
    public function canSetProperty($name, $checkVars = true)
    {
        if (parent::canSetProperty($name, $checkVars)) {
            return true;
        } else {
            return $this->hasAttribute($name);
        }
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        try {
            parent::__set($name, $value);
        } catch (\Exception $e) {
            $this->_uploadedFiles[$name] = $value;
        }
    }

    /**
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true)
    {
        if (parent::canGetProperty($name, $checkVars)) {
            return true;
        } else {
            return $this->hasAttribute($name);
        }
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        try {
            return parent::__get($name);
        } catch (\Exception $e) {
            return isset($this->_uploadedFiles[$name]) ? $this->_uploadedFiles[$name] : null;
        }
    }

    /**
     * @inheritdoc
     */
    public function load($data)
    {
        $this->normalizeAttributes();
        foreach ($this->attributes as $attribute => $storageConfig) {
            $relation = $this->owner->getRelation($storageConfig->relation);
            if (isset($data[$attribute])) {
                if ($data[$attribute]) {
                    $models = $this->getFileItemsToUpdate($data[$attribute], $relation->modelClass);
                    Model::loadMultiple($models, $data[$attribute], '');
                    $this->_relatedFiles[$attribute]['models'] = isset($this->_relatedFiles[$attribute]['models'])
                        ? array_merge($this->_relatedFiles[$attribute]['models'], $models) : $models;
                    $extraColumns = array_merge($storageConfig->extraColumns, $this->getExtraColumns($models, $data[$attribute]));
                    $this->_relatedFiles[$attribute]['extraColumns'] = isset($this->_relatedFiles[$attribute]['extraColumns'])
                        ? array_merge($this->_relatedFiles[$attribute]['extraColumns'], $extraColumns) : $extraColumns;
                } else {
                    $this->_relatedFiles[$attribute]['models'] = null;
                }
            }
        }

        return true;
    }

    /**
     * Creates uploaded file instances.
     */
    protected function getUploadedFileInstances()
    {
        foreach ($this->attributes as $attribute => $storageConfig) {
            $this->_uploadedFiles[$attribute] = $storageConfig->multiple
                ? UploadedFile::getInstances($this->owner, $attribute)
                : UploadedFile::getInstance($this->owner, $attribute);
            if (empty($this->_uploadedFiles[$attribute])) {
                $this->_uploadedFiles[$attribute] = $storageConfig->multiple
                    ? UploadedFile::getInstancesByName($attribute)
                    : UploadedFile::getInstanceByName($attribute);
            }
        }
    }

    /**
     * Deletes owner related files if new ones were uploaded.
     */
    protected function deleteRelatedFiles()
    {
        $this->normalizeAttributes();
        foreach ($this->attributes as $attribute => $storageConfig) {
            $delete = $storageConfig->deleteOnUnlink || $this->owner->getRelationSetting($storageConfig->relation, 'deleteOnUnlink');
            $filesystemComponent = $this->getFilesystemComponent();
            /** @var FileInterface[] $files */
            $files = $this->owner->getRelation($storageConfig->relation)->all();
            foreach ($files as $file) {
                $filesystemComponent->deleteFile($file);
            }
            $this->owner->unlinkAll($storageConfig->relation, $delete);
            if (isset($storageConfig->events['afterDeleteAll']) && $storageConfig->events['afterDeleteAll'] instanceof \Closure) {
                call_user_func($storageConfig->events['afterDeleteAll'], $storageConfig, $filesystemComponent);
            }
        }
    }

    /**
     * Saves uploaded files.
     */
    protected function saveUploadedFiles()
    {
        if ($this->_uploadedFiles) {
            foreach ($this->attributes as $attribute => $storageConfig) {
                if (isset($this->_uploadedFiles[$attribute])) {
                    if ($this->_uploadedFiles[$attribute] instanceof UploadedFile) {
                        if ($model = $this->saveUploadedFile($this->_uploadedFiles[$attribute], $storageConfig)) {
                            $this->_relatedFiles[$attribute]['models'][] = $model;
                        }
                    } elseif (is_array($this->_uploadedFiles[$attribute])) {
                        foreach ($this->_uploadedFiles[$attribute] as $file) {
                            if ($model = $this->saveUploadedFile($file, $storageConfig)) {
                                $this->_relatedFiles[$attribute]['models'][] = $model;
                            }
                        }
                    }
                    unset($this->_uploadedFiles[$attribute]);
                }
            }
        }
    }

    protected function linkRelatedFiles()
    {
        if ($this->_relatedFiles) {
            foreach ($this->attributes as $attribute => $storageConfig) {
                if (array_key_exists($attribute, $this->_relatedFiles)) {

//                    $extraColumns = $storageConfig->extraColumns || $this->owner->getRelationSetting($storageConfig->relation, 'extraColumns');
//                    $extraColumns = $extraColumns ?: [];
//                    $relation = $this->owner->getRelation($storageConfig->relation);
//                    if ($extraColumns && $relation->via === null) {
//                        foreach ($this->_relatedFiles[$attribute]['models'] as $model) {
//                            foreach ($extraColumns as $attr => $value) {
//                                $model->$attr = $value;
//                            }
//                        }
//                    }
//                    foreach ($this->_relatedFiles[$attribute]['models'] as $model) {
//                        $this->owner->link($storageConfig->relation, $model, $extraColumns);
//                    }

                    $this->owner->{$storageConfig->relation} = $this->_relatedFiles[$attribute]['models'];

                    unset($this->_relatedFiles[$attribute]);
                }
            }
            $this->owner->save(false);
        }
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param StorageConfig $config
     * @return FileInterface|null
     */
    protected function saveUploadedFile(UploadedFile $uploadedFile, StorageConfig $config)
    {
        $relation = $this->owner->getRelation($config->relation);
        /** @var FileInterface $fileClass */
        $fileClass = $relation->modelClass;
        $file = $fileClass::getInstanceFromUploadedFile($uploadedFile);
        $filesystemComponent = $this->getFilesystemComponent();
        $path = $config->resolveFilePath($uploadedFile->name, $this->owner);
        if (isset($config->events['beforeSave']) && $config->events['beforeSave'] instanceof \Closure) {
            call_user_func($config->events['beforeSave'], $file, $path, $config->filesystem);
        }
        if ($path = $filesystemComponent->saveFile($file, $config->filesystem, $path, false, true)) {
            $file->setPath($path);
            $file->setFilesystemName($config->filesystem);
            return $file;
        } else {
            return null;
        }
    }

    /**
     * @return FilesystemComponent
     * @throws \yii\base\InvalidConfigException
     */
    protected function getFilesystemComponent()
    {
        if (!$this->_filesystemComponent) {
            $this->_filesystemComponent = Yii::$app->get('filesystem');
        }

        return $this->_filesystemComponent;
    }

    /**
     * Creates storage config objects from config array of attributes.
     */
    protected function normalizeAttributes()
    {
        foreach ($this->attributes as $attribute => $storageConfig) {
            $this->normalizeAttribute($attribute);
        }
    }

    /**
     * @param string $name
     */
    protected function normalizeAttribute($name)
    {
        if (!$this->attributes[$name] instanceof $this->storageConfigClass) {
            $this->attributes[$name] = new $this->storageConfigClass($this->attributes[$name]);
        }
    }

    /**
     * @return bool
     */
    protected function hasUploadedFiles()
    {
        return !empty($_FILES);
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function hasAttribute($name)
    {
        return isset($this->attributes[$name]);
    }

    /**
     * @param $data
     * @param string $modelClass
     * @return array
     */
    protected function getFileItemsToUpdate($data, $modelClass)
    {
        /** @var ActiveRecord $modelClass */
        $items = [];
        $keys = [];
        $existingItems = [];
        $pks = $modelClass::primaryKey();
        if (count($pks) === 1) {
            $pk = $pks[0];
            foreach ($data as $key => $item) {
                $keys[$key] = $item[$pk];
            }
        } else {
            foreach ($data as $key => $item) {
                $kk = [];
                foreach ($pks as $pk) {
                    $kk[$pk] = $item[$pk];
                }
                $keys[$key] = $kk;
            }
        }
        if ($keys) {
            $existingItems = $modelClass::find()->where(['in', $pks, $keys])->all();
        }
        foreach ($data as $key => $itemData) {
            $exists = false;
            foreach ($existingItems as $existingItem) {
                if ($existingItem->getPrimaryKey() == $keys[$key]) {
                    $items[$key] = $existingItem;
                    $exists = true;
                    break;
                }
            }
            if (!$exists) {
                $items[$key] = new $modelClass($itemData);
            }
        }

        return $items;
    }

    /**
     * Returns extra columns for models from data array.
     *
     * @param Model[] $models
     * @param array $data
     * @return array
     */
    protected function getExtraColumns($models, $data)
    {
        $extraColumns = [];
        foreach ($models as $key => $model) {
            if (!empty($data[$key])) {
                $extra = array_diff(array_keys($data[$key]), $model->safeAttributes());
                if ($extra) {
                    $extraColumns[$key] = array_intersect_key($data[$key], array_flip($extra));
                }
            }
        }

        return $extraColumns;
    }
}