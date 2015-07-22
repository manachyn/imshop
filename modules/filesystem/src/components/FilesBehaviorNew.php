<?php

namespace im\filesystem\components;

use im\filesystem\models\DbFile;
use im\filesystem\models\File;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * Class FilesBehavior adds possibility to handle file uploads and link them to the model.
 *
 * @property ActiveRecord $owner
 * @package im\filesystem\components
 */
class FilesBehaviorNew extends Behavior
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
    private $_files;

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
        $this->normalizeAttributes();
        $this->getUploadedFileInstances();
    }

    /**
     * Handles before save event of owner.
     * Deletes old related files, creates file instances for uploaded files and sets them to owner's attributes before saving.
     */
    public function beforeSave()
    {
        $this->deleteRelatedFiles();
        $this->getFileInstances();
    }

    /**
     * Handles after update event of owner.
     * Saves uploaded files to filesystem.
     */
    public function afterUpdate()
    {
        foreach ($this->attributes as $attribute => $storageConfig) {
            if (!empty($this->_files[$attribute] )) {
                if ($this->_files[$attribute] instanceof UploadedFile) {
                    $this->saveFile($this->_files[$attribute], $storageConfig);
                } elseif (is_array($this->_files[$attribute]) && !empty($this->_files[$attribute])) {
                    $this->saveFiles((array) $this->_files[$attribute], $storageConfig);
                }
            }
        }
    }

    /**
     * Handles after insert event of owner.
     * Updates related files for in case where file name contains owner primary key.
     */
    public function afterInsert()
    {
        $update = false;
        foreach ($this->attributes as $attribute => $storageConfig) {
            if ($this->_files[$attribute] && $storageConfig->updateAfterCreation) {
                $value = $this->owner->$attribute;
                if ($this->_files[$attribute] instanceof UploadedFile) {
                    /** @var FileInterface $value */
                    $value->setPath($storageConfig->resolveFilePath($value->getPath(), $this->owner));
                } elseif (is_array($this->_files[$attribute])) {
                    /** @var FileInterface[] $value */
                    foreach ($value as $item) {
                        $item->setPath($storageConfig->resolveFilePath($item->getPath(), $this->owner));
                    }
                }
                $this->owner->$attribute = $value;
                $update = true;
            }
        }

        if ($update) {
            $this->owner->update();
        }
    }

    /**
     * Creates uploaded file instances and sets them to owner's attributes.
     */
    protected function getUploadedFileInstances()
    {
        foreach ($this->attributes as $attribute => $storageConfig) {
            $this->_files[$attribute] = $storageConfig->multiple ? UploadedFile::getInstances($this->owner, $attribute)
                : UploadedFile::getInstance($this->owner, $attribute);
            if (empty($this->_files[$attribute])) {
                $this->_files[$attribute] = $storageConfig->multiple ? UploadedFile::getInstancesByName($attribute)
                    : UploadedFile::getInstanceByName($attribute);
            }
            if ($this->_files[$attribute]) {
                if ($this->_files[$attribute] instanceof UploadedFile) {
                    $this->owner->$attribute = $this->_files[$attribute];
                } elseif (is_array($this->_files[$attribute])) {
                    $this->owner->$attribute = array_filter((array)$this->_files[$attribute], function ($file) {
                        return $file instanceof UploadedFile;
                    });
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
            if (!$this->owner->isNewRecord && !empty($this->_files[$attribute])) {
                //$this->deleteFiles($attribute, $storageConfig);
            }
        }
    }

    /**
     * Creates file instances for uploaded files and sets them to owner's attributes.
     */
    protected function getFileInstances()
    {
        foreach ($this->attributes as $attribute => $storageConfig) {
            if (!empty($this->_files[$attribute] )) {
                /** @var DbFile|File $fileClass */
                $fileClass = $storageConfig->dbInstance ? DbFile::className() : File::className();
                if ($this->_files[$attribute] instanceof UploadedFile) {
                    $this->owner->$attribute = $fileClass::getInstance([
                        'filesystem' => $storageConfig->getFilesystem(),
                        'path' => $storageConfig->resolveFilePath($this->_files[$attribute]->name, $this->owner),
                        'mimeType' => $this->_files[$attribute]->type,
                        'size' => $this->_files[$attribute]->size
                    ]);
                } elseif (is_array($this->_files[$attribute])) {
                    $files = $this->_files[$attribute];
                    $this->owner->$attribute = array_map(function($index) use ($files, $storageConfig, $fileClass) {
                        return $fileClass::getInstance([
                            'filesystem' => $storageConfig->getFilesystem(),
                            'path' => $storageConfig->resolveFilePath($files[$index], $this->owner, $index + 1),
                            'mimeType' => $files[$index]->type,
                            'size' => $files[$index]->size
                        ]);
                    }, array_keys((array) $files));
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
            if (!$storageConfig instanceof $this->storageConfigClass) {
                $this->attributes[$attribute] = new $this->storageConfigClass($storageConfig);
            }
        }
    }

//    protected function deleteFile($file, StorageConfig $config)
//    {
//        $filePath = rtrim($config->resolvePath($this->owner), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;
//        if ($filesystem = $config->getFilesystemInstance()) {
//            if ($filesystem->has($filePath)) {
//                $filesystem->delete($filePath);
//            }
//        } else {
//            @unlink($filePath);
//        }
//    }
//
//    protected function deleteFiles($attribute, StorageConfig $config)
//    {
//        $oldFiles = $config->multiple ? json_decode($this->owner->getOldAttribute($attribute)) : $this->owner->getOldAttribute($attribute);
//        if ($oldFiles) {
//            $oldFiles = (array) $oldFiles;
//            foreach ($oldFiles as $file) {
//                $this->deleteFile($file, $config);
//            }
//        }
//    }
}