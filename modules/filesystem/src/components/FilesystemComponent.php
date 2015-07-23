<?php

namespace im\filesystem\components;

use creocoder\flysystem\Filesystem;
use im\filesystem\events\FilesystemEvent;
use im\filesystem\exception\FilesystemException;
use im\filesystem\models\File;
use yii\base\Component;
use yii\base\InvalidParamException;
use yii\di\Instance;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Filesystem component class
 */
class FilesystemComponent extends Component
{
    /**
     * @event FilesystemEvent an event that is triggered before file saving.
     */
    const EVENT_BEFORE_SAVE = 'beforeSave';

    /**
     * @event FilesystemEvent an event that is triggered after file was saved.
     */
    const EVENT_AFTER_SAVE = 'afterSave';

    /**
     * @var array of available filesystems
     */
    public $filesystems = [];

    /**
     * Returns filesystem by name.
     *
     * @param string $filesystem name
     * @return null|Filesystem
     */
    public function get($filesystem)
    {
        if (isset($this->filesystems[$filesystem])) {
            if (!$this->filesystems[$filesystem] instanceof Filesystem) {
                return $this->filesystems[$filesystem] = Instance::ensure($this->filesystems[$filesystem], Filesystem::className());
            } else {
                return $this->filesystems[$filesystem];
            }
        } else {
            return null;
        }
    }

//    /**
//     * Save uploaded file to the path.
//     *
//     * @param UploadedFile $file
//     * @param $path
//     * @param StorageConfig $config
//     * @param int $index
//     * @throws \Exception
//     * @return bool whether the file was saved.
//     */
//    public function saveUploadedFile(UploadedFile $file, $path, StorageConfig $config = null)
//    {
//        if ($config && $config->filesystem && $filesystem = $this->get($config->filesystem)) {
//            if ($file->error == UPLOAD_ERR_OK && is_uploaded_file($file->tempName)) {
//                $stream = fopen($file->tempName, 'r+');
//                return $filesystem->writeStream($path, $stream, ['visibility' => $config->visibility]);
//            }
//        } else {
//            $dir = pathinfo($path, PATHINFO_DIRNAME);
//            if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
//                throw new FilesystemException("Can't create the directory '$dir'");
//            }
//            return $file->saveAs($path);
//        }
//
//        return false;
//    }

    public function saveFile(FileInterface $file, $filesystem = null, $path = null, $overwrite = false)
    {
        $saved = false;
        if ($filesystem && $filesystem = $this->get($filesystem)) {
            $this->beforeSave($file, $filesystem, $path);
            $path = $path ?: $file->getPath();
            $stream = fopen($file->getPath(), 'r+');
            if ($overwrite) {
                $saved = $filesystem->putStream($path, $stream);
            } else {
                $saved = $filesystem->writeStream($path, $stream);
            }
            fclose($stream);
        } elseif ($path) {
            $this->beforeSave($file, null, $path);
            $dir = pathinfo($path, PATHINFO_DIRNAME);
            if (!is_dir($dir) && FileHelper::createDirectory($dir)) {
                $saved = is_uploaded_file($file->getPath())
                    ? move_uploaded_file($file->getPath(), $path): copy($file->getPath(), $path);
            }
        } else {
            throw new InvalidParamException('Filesystem or file path should be specified.');
        }

        if ($saved) {
            $this->afterSave($file, $filesystem, $path);
            return $path;
        }

        return false;
    }

    public function getUrl(FileInterface $file, $filesystem)
    {
        $filesystem = $this->get($filesystem);
        //$filesystem->get

        return false;
    }

    /**
     * Called before file saving.
     *
     * @param FileInterface $file
     * @param Filesystem $filesystem
     * @param string $path
     */
    protected function beforeSave(FileInterface $file, Filesystem $filesystem, $path = null)
    {
        $event = new FilesystemEvent(['file' => $file, 'filesystem' => $filesystem, 'path' => $path]);
        $this->trigger(self::EVENT_BEFORE_SAVE, $event);
    }

    /**
     * Called after file saving.
     *
     * @param FileInterface $file
     * @param Filesystem $filesystem
     * @param string $path
     */
    protected function afterSave(FileInterface $file, Filesystem $filesystem, $path = null)
    {
        $event = new FilesystemEvent(['file' => $file, 'filesystem' => $filesystem, 'path' => $path]);
        $this->trigger(self::EVENT_AFTER_SAVE, $event);
    }
} 