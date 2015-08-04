<?php

namespace im\filesystem\components;

use im\filesystem\models\DbFile;
use im\filesystem\models\File;
use yii\base\Behavior;
use yii\base\UnknownMethodException;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use Yii;

/**
 * Class FilesBehavior adds possibility to handle file uploads and link them to the model.
 *
 * @property ActiveRecord $owner
 * @package im\filesystem\components
 */
class FilesBehaviorOldd extends Behavior
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
     * @var FileInterface[]
     */
    private $_files = [];

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
//            ActiveRecord::EVENT_BEFORE_DELETE => 'beforeDelete',
        ];
    }

    /**
     * Handles before validate event of owner.
     * Creates instances of uploaded files and sets them to owner's attributes for validation.
     */
    public function beforeValidate()
    {
        if ($this->hasUploadedFiles()) {
            $this->normalizeAttributes();
            $this->getUploadedFileInstances();
            $this->setFileAttributes();
        }
    }

    /**
     * Handles before save event of owner.
     * Deletes old related files, creates file instances for uploaded files and sets them to owner's attributes before saving.
     */
    public function beforeSave()
    {
//        if ($this->hasUploadedFiles()) {
//            $this->deleteRelatedFiles();
//            $this->getFileInstances();
//        }
    }

    /**
     * Handles after update event of owner.
     * Saves uploaded files to filesystem.
     */
    public function afterUpdate()
    {
        $this->saveUploadedFiles();
    }

    /**
     * Handles after insert event of owner.
     * Updates related files for in case where file name contains owner primary key.
     */
    public function afterInsert()
    {
        $this->saveUploadedFiles();
//        $update = false;
//        foreach ($this->attributes as $attribute => $storageConfig) {
//            if ($this->_uploadedFiles[$attribute] && $storageConfig->updateAfterCreation) {
//                $value = $this->owner->$attribute;
//                if ($this->_uploadedFiles[$attribute] instanceof UploadedFile) {
//                    /** @var FileInterface $value */
//                    $value->setPath($storageConfig->resolveFilePath($value->getPath(), $this->owner));
//                } elseif (is_array($this->_uploadedFiles[$attribute])) {
//                    /** @var FileInterface[] $value */
//                    foreach ($value as $item) {
//                        $item->setPath($storageConfig->resolveFilePath($item->getPath(), $this->owner));
//                    }
//                }
//                $this->owner->$attribute = $value;
//                $update = true;
//            }
//        }
//
//        if ($update) {
//            $this->owner->update();
//        }
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
        try { parent::__set($name, $value); }
        catch (\Exception $e) {
            $this->setAttribute($name, $value);
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
        try { return parent::__get($name); }
        catch (\Exception $e) {
            return isset($this->_uploadedFiles[$name]) ? $this->_uploadedFiles[$name] : null;
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
            return $this->getRelation($name);
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
     * Sets file attributes of the owner.
     */
    protected function setFileAttributes()
    {
        if ($this->_uploadedFiles) {
            foreach ($this->attributes as $attribute => $storageConfig) {
                if ($this->_uploadedFiles[$attribute]) {
                    if ($this->_uploadedFiles[$attribute] instanceof UploadedFile) {
                        $this->owner->$attribute = $this->_uploadedFiles[$attribute];
                    } elseif (is_array($this->_uploadedFiles[$attribute])) {
                        $this->owner->$attribute = array_filter((array)$this->_uploadedFiles[$attribute], function ($file) {
                            return $file instanceof UploadedFile;
                        });
                    }
                }
            }
        }
    }

    /**
     * Deletes owner related files if new ones were uploaded.
     */
    protected function deleteRelatedFiles()
    {
        foreach ($this->attributes as $attribute => $storageConfig) {
            if (!$this->owner->isNewRecord && !empty($this->_uploadedFiles[$attribute])) {
                //$this->deleteFiles($attribute, $storageConfig);
            }
        }
    }

    /**
     * Creates file instances for uploaded files and sets them to owner's attributes.
     */
    protected function getFileInstances()
    {
        if ($this->_uploadedFiles) {
            foreach ($this->attributes as $attribute => $storageConfig) {
                if ($this->_uploadedFiles[$attribute]) {
                    if ($this->_uploadedFiles[$attribute] instanceof UploadedFile) {
                        $file = $this->createFileInstance($this->_uploadedFiles[$attribute], $storageConfig);
                        $this->_files[$attribute][] = $file;
                    } elseif (is_array($this->_uploadedFiles[$attribute])) {
                        foreach ($this->_uploadedFiles[$attribute] as $item) {
                            $file = $this->createFileInstance($item, $storageConfig);
                            $this->_files[$attribute][] = $file;
                        }
                    }
                }
            }
        }
    }

    /**
     * Creates file instance.
     *
     * @param UploadedFile $file
     * @param StorageConfig $config
     * @param int $fileIndex
     * @return FileInterface
     */
    protected function createFileInstance(UploadedFile $file, StorageConfig $config, $fileIndex = 1)
    {
        /** @var DbFile|File $fileClass */
        $fileClass = $config->relation ? DbFile::className() : File::className();

        return $fileClass::getInstance([
            'filesystem' => $config->filesystem,
            'path' => $config->resolveFilePath($file->name, $this->owner, $fileIndex),
            'mimeType' => $file->type,
            'size' => $file->size
        ]);
    }

    /**
     * Saves uploaded files.
     */
    protected function saveUploadedFiles()
    {
        if ($this->_uploadedFiles) {
            /** @var FilesystemComponent $filesystemComponent */
            $filesystemComponent = Yii::$app->get('filesystem');
            foreach ($this->attributes as $attribute => $storageConfig) {
                if ($this->_uploadedFiles[$attribute]) {
                    if ($this->_uploadedFiles[$attribute] instanceof UploadedFile) {
                        $file = $this->createFileInstance($this->_uploadedFiles[$attribute], $storageConfig);
                        unset($this->_uploadedFiles[$attribute]);
                        $this->_files[$attribute][] = $file;
                        if ($storageConfig->relation) {
                            /** @var DbFile $file */
                            if ($file->save()) {
                                $this->owner->link($attribute, $file);
                            }
                        }

                        $a = 1;
//                        $path = $storageConfig->resolveFilePath($this->_uploadedFiles[$attribute]->name, $this->owner);
//                        $filesystemComponent->saveUploadedFile($this->_uploadedFiles[$attribute], $path, $storageConfig);
                    } elseif (is_array($this->_uploadedFiles[$attribute])) {
                        foreach ($this->_uploadedFiles[$attribute] as $index => $item) {
                            $file = $this->createFileInstance($item, $storageConfig, $index + 1);
                            $this->_files[$attribute][] = $file;
//                            $path = $storageConfig->resolveFilePath($item->name, $this->owner, $index + 1);
//                            $filesystemComponent->saveUploadedFile($item, $path, $storageConfig);
                        }
                    }
                }
            }
        }
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

    protected function setAttribute($name, $value)
    {
        if ($value instanceof UploadedFile) {
            $this->_uploadedFiles[$name] = $value;
//            if ($this->hasRelation($name)) {
//                $this->owner->populateRelation($name, $value);
//            }
        } elseif ($value instanceof FileInterface) {
            $this->_uploadedFiles[$name] = $value;
//            if ($this->hasRelation($name)) {
//                $this->owner->populateRelation($name, $value);
//            }
        }
    }

    protected function getRelation($name)
    {
        if ($this->hasAttribute($name)) {
            $this->normalizeAttribute($name);
            return $this->attributes[$name]->relation;
        } else {
            return null;
        }
    }
}