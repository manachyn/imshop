<?php

namespace im\filesystem\components;

use yii\base\Behavior;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use Yii;

class UploadBehavior extends Behavior
{
    /**
     * @var ActiveRecord
     */
    public $owner;

    /**
     * @var UploadConfig[]
     */
    public $attributes = [];

    /**
     * @var string default upload config class
     */
    public $uploadConfigClass = 'app\modules\filesystem\components\UploadConfig';

    /**
     * @var UploadConfig default upload config for all attributes
     */
    public $uploadConfig;

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
        foreach ($this->attributes as $attribute => $uploadConfig) {
            $this->_files[$attribute] = $uploadConfig->multiple ? UploadedFile::getInstances($this->owner, $attribute)
                : UploadedFile::getInstance($this->owner, $attribute);
            if (empty($this->_files[$attribute])) {
                $this->_files[$attribute] = $uploadConfig->multiple ? UploadedFile::getInstancesByName($attribute)
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
        foreach ($this->attributes as $attribute => $uploadConfig) {
            if (!empty($this->_files[$attribute] )) {
                if (!$this->owner->isNewRecord) {
                    $this->deleteFiles($attribute, $uploadConfig);
                }
                if ($this->_files[$attribute] instanceof UploadedFile) {
                    $this->owner->$attribute = pathinfo($uploadConfig->resolveFileName($this->_files[$attribute]->name, $this->owner), PATHINFO_BASENAME);
                } elseif (is_array($this->_files[$attribute])) {
                    $files = $this->_files[$attribute];
                    $this->owner->$attribute = json_encode(array_map(function($index) use ($files, $uploadConfig) {
                        return pathinfo($uploadConfig->resolveFileName($files[$index], $this->owner, $index + 1), PATHINFO_BASENAME);
                    }, array_keys((array) $files)));
                }
            }
        }
    }

    /**
     * After update event.
     */
    public function afterUpdate()
    {
        foreach ($this->attributes as $attribute => $uploadConfig) {
            if (!empty($this->_files[$attribute] )) {
                if ($this->_files[$attribute] instanceof UploadedFile) {
                    $this->saveFile($this->_files[$attribute], $uploadConfig);
                } elseif (is_array($this->_files[$attribute]) && !empty($this->_files[$attribute])) {
                    $this->saveFiles((array) $this->_files[$attribute], $uploadConfig);
                }
            }
        }
    }

    /**
     * After insert event.
     */
    public function afterInsert()
    {
        foreach ($this->attributes as $attribute => $uploadConfig) {
            if ($this->_files[$attribute] instanceof UploadedFile) {
                if ($filePath = $this->saveFile($this->_files[$attribute], $uploadConfig)) {
                    $this->owner->updateAttributes([$attribute => pathinfo($filePath, PATHINFO_BASENAME)]);
                }
            } elseif (is_array($this->_files[$attribute]) && !empty($this->_files[$attribute])) {
                $paths = $this->saveFiles((array) $this->_files[$attribute], $uploadConfig);
                if ($paths) {
                    foreach ($paths as $key => $path) {
                        $paths[$key] = pathinfo($path, PATHINFO_BASENAME);
                    }
                    $this->owner->updateAttributes([$attribute => json_encode($paths)]);
                }
            }
        }
    }

    /**
     * @param UploadedFile $file
     * @param UploadConfig $config
     * @param int $index
     * @throws \Exception
     * @return bool|string
     */
    public function saveFile(UploadedFile $file, UploadConfig $config, $index = 1)
    {
        if (!empty($config->filesystem)) {
            if ($file->error == UPLOAD_ERR_OK && is_uploaded_file($file->tempName)) {
                $filePath = $config->resolveFilePath($file->name, $this->owner, $index);
                $stream = fopen($file->tempName, 'r+');
                if ($config->filesystem->writeStream($filePath, $stream, [
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
     * @param UploadConfig $config
     * @return array
     * @throws \Exception
     */
    public function saveFiles($files, UploadConfig $config)
    {
        $saved = [];
        foreach ($files as $index => $file) {
            $this->saveFile($file, $config, $index + 1);
        }

        return $saved;
    }

    public function deleteFile($file, UploadConfig $config)
    {
        $filePath  = rtrim($config->resolvePath($this->owner), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $file;
        if (!empty($config->filesystem)) {
            if ($config->filesystem->has($filePath)) {
                $config->filesystem->delete($filePath);
            }
        } else {
            @unlink($filePath);
        }
    }

    public function deleteFiles($attribute, UploadConfig $config)
    {
        if ($oldFiles = json_decode($this->owner->getOldAttribute($attribute))) {
            $oldFiles = (array) $oldFiles;
            foreach ($oldFiles as $file) {
                $this->deleteFile($file, $config);
            }
        }
    }

    protected function normalizeAttributes()
    {
        foreach ($this->attributes as $attribute => $uploadConfig) {
            if (is_numeric($attribute)) {
                unset($this->attributes[$attribute]);
                $attribute = $uploadConfig;
                $uploadConfig = [];
            }
            $uploadConfig = (array) $uploadConfig;
            if (empty($uploadConfig['class'])) {
                $uploadConfig['class'] = $this->uploadConfigClass;
            }
            $this->attributes[$attribute] = Yii::createObject($uploadConfig);
        }
    }
}