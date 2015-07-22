<?php

namespace im\filesystem\components;

use im\filesystem\models\DbFile;
use im\filesystem\models\File;
use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use Yii;

class FilesBehavior extends Behavior
{
    /**
     * @var ActiveRecord
     */
    public $owner;

    /**
     * @var StorageConfig[]
     */
    public $attributes = [];

    /**
     * @var string default storage config class
     */
    public $storageConfigClass = 'im\filesystem\components\StorageConfig';

    /**
     * @var StorageConfig default upload config for all attributes
     */
    public $storageConfig;

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
     * Before validate event.
     */
    public function beforeValidate()
    {
        $this->normalizeAttributes();
        foreach ($this->attributes as $attribute => $storageConfig) {
            $this->_files[$attribute] = $storageConfig->multiple ? UploadedFile::getInstances($this->owner, $attribute)
                : UploadedFile::getInstance($this->owner, $attribute);
            if (empty($this->_files[$attribute])) {
                $this->_files[$attribute] = $storageConfig->multiple ? UploadedFile::getInstancesByName($attribute)
                    : UploadedFile::getInstanceByName($attribute);
            }
            if ($this->_files[$attribute] instanceof UploadedFile) {
                $this->owner->$attribute = $this->_files[$attribute];
            } elseif (is_array($this->_files[$attribute]) && !empty($this->_files[$attribute])) {
                $this->owner->$attribute = array_filter((array) $this->_files[$attribute], function($file) {
                    return $file instanceof UploadedFile;
                });
            }
        }
    }

    /**
     * Before save event.
     */
    public function beforeSave()
    {
        foreach ($this->attributes as $attribute => $storageConfig) {
            if (!empty($this->_files[$attribute] )) {
                if (!$this->owner->isNewRecord) {
                    $this->deleteFiles($attribute, $storageConfig);
                }
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
     * After update event.
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
     * After insert event.
     */
    public function afterInsert()
    {
        $this->owner->save(false);
//        foreach ($this->attributes as $attribute => $storageConfig) {
//            if ($this->_files[$attribute] instanceof UploadedFile) {
//                if ($filePath = $this->saveFile($this->_files[$attribute], $storageConfig)) {
//                    $this->owner->updateAttributes([$attribute => pathinfo($filePath, PATHINFO_BASENAME)]);
//                }
//            } elseif (is_array($this->_files[$attribute]) && !empty($this->_files[$attribute])) {
//                $paths = $this->saveFiles((array) $this->_files[$attribute], $storageConfig);
//                if ($paths) {
//                    foreach ($paths as $key => $path) {
//                        $paths[$key] = pathinfo($path, PATHINFO_BASENAME);
//                    }
//                    $this->owner->updateAttributes([$attribute => json_encode($paths)]);
//                }
//            }
//        }
    }

    /**
     * @param UploadedFile $file
     * @param StorageConfig $config
     * @param int $index
     * @throws \Exception
     * @return bool|string
     */
    public function saveFile(UploadedFile $file, StorageConfig $config, $index = 1)
    {
        if ($filesystem = $config->getFilesystemInstance()) {
            if ($file->error == UPLOAD_ERR_OK && is_uploaded_file($file->tempName)) {
                $filePath = $config->resolveFilePath($file->name, $this->owner, $index);
                $stream = fopen($file->tempName, 'r+');
                if ($filesystem->writeStream($filePath, $stream, [
                    'visibility' => $config->visibility
                ])) {
                    return $filePath;
                }
            }
        } else {
            $filePath = $config->resolveFilePath($file->name, $this->owner, $index);
            $dir = pathinfo($filePath, PATHINFO_DIRNAME);
            if (!is_dir($dir)) {
                if(!mkdir($dir, 0755, true)) {
                    throw new \Exception("Can't create the directory '$dir'");
                }
            }
            if ($file->saveAs($filePath)) {
                return $filePath;
            }
        }

        return false;
    }

    /**
     * @param UploadedFile[] $files
     * @param StorageConfig $config
     * @return array
     * @throws \Exception
     */
    public function saveFiles($files, StorageConfig $config)
    {
        $saved = [];
        foreach ($files as $index => $file) {
            $this->saveFile($file, $config, $index + 1);
        }

        return $saved;
    }

    public function deleteFile($file, StorageConfig $config)
    {
        $filePath = rtrim($config->resolvePath($this->owner), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;
        if ($filesystem = $config->getFilesystemInstance()) {
            if ($filesystem->has($filePath)) {
                $filesystem->delete($filePath);
            }
        } else {
            @unlink($filePath);
        }
    }

    public function deleteFiles($attribute, StorageConfig $config)
    {
        $oldFiles = $config->multiple ? json_decode($this->owner->getOldAttribute($attribute)) : $this->owner->getOldAttribute($attribute);
        if ($oldFiles) {
            $oldFiles = (array) $oldFiles;
            foreach ($oldFiles as $file) {
                $this->deleteFile($file, $config);
            }
        }
    }

//    /**
//     * @inheritdoc
//     */
//    public function canGetProperty($name, $checkVars = true)
//    {
//        if (parent::canGetProperty($name, $checkVars)) {
//            return true;
//        } else {
//            return $this->hasAttribute($name);
//        }
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function __get($name)
//    {
//        try { return parent::__get($name); }
//        catch (\Exception $e) {
//            return [];
//        }
//    }

    /**
     * @param string $name
     * @return bool
     */
    protected function hasAttribute($name)
    {
        return isset($this->attributes[$name]);
    }

    protected function normalizeAttributes()
    {
        foreach ($this->attributes as $attribute => $storageConfig) {
            if (!$storageConfig instanceof $this->storageConfigClass) {
                if (is_numeric($attribute)) {
                    unset($this->attributes[$attribute]);
                    $attribute = $storageConfig;
                    $storageConfig = [];
                }
                $storageConfig = (array)$storageConfig;
                $this->attributes[$attribute] = new $this->storageConfigClass($storageConfig);
            }
        }
    }
}