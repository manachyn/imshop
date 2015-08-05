<?php

namespace im\filesystem\components;

use im\base\interfaces\ModelBehaviorInterface;
use im\filesystem\models\DbFile;
use yii\base\Behavior;
use yii\base\UnknownMethodException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\validators\Validator;
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
     * @var ActiveQuery[]
     */
    public $relations = [];

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
     * @var FileInterface[]
     */
    private $_files = [];

    /**
     * @var FilesystemComponent
     */
    private $_filesystemComponent;

    /**
     * @inheritdoc
     */
    public function attach($owner)
    {
        parent::attach($owner);
        $validators = $this->owner->getValidators();
        $validator = Validator::createValidator('im\base\validators\RelationValidator', $this->owner, ['images']);
        $validators->append($validator);
    }

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
//            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
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
        //$this->saveUploadedFiles();
        $this->saveRelatedFiles();
    }

    /**
     * @inheritdoc
     */
    public function canSetProperty($name, $checkVars = true)
    {
        if (parent::canSetProperty($name, $checkVars)) {
            return true;
        } else {
            if (strncmp($name, 'uploaded', 8) === 0) {
                $name = strtolower(substr($name, 8));
            }
            return $this->hasAttribute($name);
        }
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        try { parent::__set($name, $value); }
        catch (\Exception $e) {
            if (strncmp($name, 'uploaded', 8 === 0)) {
                $name = strtolower(substr($name, 8));
                $this->_uploadedFiles[$name] = $value;
            }
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
            if (strncmp($name, 'uploaded', 8) === 0) {
                $name = strtolower(substr($name, 8));
            }
            return $this->hasAttribute($name);
        }
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        try { return parent::__get($name); }
        catch (\Exception $e) {
            if (strncmp($name, 'uploaded', 8 === 0)) {
                $name = strtolower(substr($name, 8));
                if (isset($this->_uploadedFiles[$name])) {
                    return $this->_uploadedFiles[$name];
                }
            } elseif ($this->hasRelation($name)) {
                return $this->getRelation($name);
            }

            return null;
        }
    }

    /**
     * @inheritdoc
     */
    public function hasMethod($name)
    {
        if (parent::hasMethod($name)) {
            return true;
        } else {
            if (strncmp($name, 'get', 3) === 0) {
                $name = substr($name, 3);
            }
            return $this->hasRelation($name);
        }
    }

    /**
     * @inheritdoc
     */
    public function __call($name, $params)
    {
        if (strncmp($name, 'get', 3) === 0) {
            $name = substr($name, 3);
        }
        if ($relation = $this->getRelation($name)) {
            return $relation;
        }

        throw new UnknownMethodException('Calling unknown method: ' . get_class($this) . "::$name()");
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

    }

    /**
     * Saves uploaded files.
     */
    protected function saveUploadedFiles()
    {
        if ($this->_uploadedFiles) {
            foreach ($this->attributes as $attribute => $storageConfig) {
                if ($this->_uploadedFiles[$attribute]) {
                    if ($this->_uploadedFiles[$attribute] instanceof UploadedFile) {
                        if ($file = $this->saveUploadedFile($this->_uploadedFiles[$attribute], $storageConfig)) {
                            $this->_files[$attribute] = $file;
                            unset($this->_uploadedFiles[$attribute]);
                            if ($storageConfig->relation) {
                                /** @var DbFile $file */
                                if ($file->save()) {
                                    $this->owner->link($attribute, $file);
                                }
                            } else {
                                $this->owner->{$attribute . '_data'} = serialize($file);
                            }
                        }
                    } elseif (is_array($this->_uploadedFiles[$attribute])) {
                        foreach ($this->_uploadedFiles[$attribute] as $index => $item) {
                            if ($file = $this->saveUploadedFile($item, $storageConfig)) {
                                $this->_files[$attribute][] = $file;
                            }
                        }
                        unset($this->_uploadedFiles[$attribute]);
                        if ($storageConfig->relation) {
                            foreach ($this->_files[$attribute] as $file) {
                                /** @var DbFile $file */
                                if ($file->save()) {
                                    $this->owner->link($attribute, $file);
                                }
                            }
                        } else {
                            $this->owner->{$attribute . '_data'} = serialize($this->_files[$attribute]);
                        }
                    }
                }
            }
        }
    }

    protected function saveRelatedFiles()
    {
        if ($this->_relatedFiles) {
            foreach ($this->attributes as $attribute => $storageConfig) {
                if (isset($this->_relatedFiles[$attribute])) {
                    $this->owner->unlinkAll($attribute, true);
                    foreach ($this->_relatedFiles[$attribute]['models'] as $i => $model) {
                        $this->owner->link($attribute, $model, isset($this->_relatedFiles[$attribute]['extraColumns'][$i]) ? $this->_relatedFiles[$attribute]['extraColumns'][$i] : []);
                    }
                }
            }
        }
    }

    /**
     * @param UploadedFile $uploadedFile
     * @param StorageConfig $config
     * @param int $fileIndex
     * @return FileInterface|null
     */
    protected function saveUploadedFile(UploadedFile $uploadedFile, StorageConfig $config, $fileIndex = 1)
    {
        $filesystemComponent = $this->getFilesystemComponent();
        /** @var FileInterface $fileClass */
        $fileClass = $config->fileClass;
        $file = $fileClass::getInstanceFromUploadedFile($uploadedFile);
        $path = $config->resolveFilePath($uploadedFile->name, $this->owner, $fileIndex);
        if ($path = $filesystemComponent->saveFile($file, $config->filesystem, $path, true)) {
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

    protected function linkFile(FileInterface $file, StorageConfig $config)
    {

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

    protected function normalizeAttribute($name)
    {
        if (!$this->attributes[$name] instanceof $this->storageConfigClass) {
            $this->attributes[$name] = new $this->storageConfigClass($this->attributes[$name]);
        }
    }

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

    protected function hasRelation($name)
    {
        return isset($this->relations[$name]);
    }

    protected function getRelation($name)
    {
        $relation = $this->relations[$name];
        if ($relation instanceof \Closure) {
            $relation = call_user_func($relation);
        }

        return $relation;
    }

    /**
     * @inheritdoc
     */
    public function load($data)
    {
        $this->normalizeAttributes();
        foreach ($this->attributes as $attribute => $storageConfig) {
            if (isset($data[$attribute])) {
                /** @var ActiveRecord $modelClass */
                $modelClass = $storageConfig->fileClass;
                $pks = array_keys($data[$attribute]);
                $models = $modelClass::find()->where(['id' => $pks])->indexBy('id')->all();
                foreach ($models as $i => $model) {
                    if (!empty($data[$attribute][$i])) {
                        $model->load($data[$attribute][$i], '');
                        $this->_relatedFiles[$attribute]['models'][$i] = $model;
                        $extraColumns = array_diff(array_keys($data[$attribute][$i]), $model->safeAttributes());
                        if ($extraColumns) {
                            $this->_relatedFiles[$attribute]['extraColumns'][$i] = array_intersect_key($data[$attribute][$i], array_flip($extraColumns));
                        }
                    }
                }
//                if ($modelClass::loadMultiple($models, $data[$attribute], '')) {
//                    $this->owner->populateRelation($attribute, $models);
//                    $this->_relatedFiles[$attribute]['models'] = $models;
//                    $this->_relatedFiles[$attribute]['extraColumns'] = $models;
//                    $extraColumns = array_diff(reset($data[$attribute]), )
//                }
            }
        }

        return true;
    }

    protected function getFileItemsToUpdate()
    {

    }
}