<?php

namespace im\filesystem\components;

use creocoder\flysystem\Filesystem;
use im\filesystem\components\flysystem\plugins\UrlPlugin;
use im\filesystem\events\FilesystemEvent;
use Yii;
use yii\base\Component;
use yii\base\InvalidParamException;
use yii\di\Instance;
use yii\helpers\FileHelper;

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
     * @event FilesystemEvent an event that is triggered before file deleting.
     */
    const EVENT_BEFORE_DELETE = 'beforeDelete';

    /**
     * @event FilesystemEvent an event that is triggered after file was deleted.
     */
    const EVENT_AFTER_DELETE = 'afterDelete';

    /**
     * @var array|Filesystem[] available filesystems
     */
    public $filesystems = [];

    /**
     * @return Filesystem[]
     */
    public function getFilesystems()
    {
        foreach ($this->filesystems as $key => $filesystem) {
            $this->get($key);
        }

        return $this->filesystems;
    }

    /**
     * @param array|Filesystem[] $filesystems
     */
    public function setFilesystems($filesystems)
    {
        $this->filesystems = $filesystems;
    }

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

    /**
     * Save file to specified filesystem or path.
     *
     * @param FileInterface $file
     * @param Filesystem|string $filesystem
     * @param string $path
     * @param bool $overwrite
     * @param bool $resolveNameConflict
     * @return bool|string
     */
    public function saveFile(FileInterface $file, $filesystem = null, $path = null, $overwrite = false, $resolveNameConflict = false)
    {
        $saved = false;
        if ($filesystem && !$filesystem instanceof Filesystem) {
            $filesystem = $this->get($filesystem);
        }
        if ($filesystem) {
            $this->beforeSave($file, $filesystem, $path);
            $path = $path ?: $file->getPath();
            if ($resolveNameConflict) {
                $i = 0;
                $newPath = $path;
                $pathParts = pathinfo($path);
                while ($filesystem->has($newPath)) {
                    $i++;
                    $filename = $pathParts['filename'] . '-' . $i;
                    $newPath = $pathParts['dirname'] . DIRECTORY_SEPARATOR . $filename . '.' . $pathParts['extension'];
                }
                $path = $newPath;
            }
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

    /**
     * Deletes file.
     *
     * @param FileInterface $file
     * @return bool
     */
    public function deleteFile(FileInterface $file)
    {
        $filesystem = $file->getFilesystemName();
        if ($filesystem && $filesystem = $this->get($filesystem)) {
            $this->beforeDelete($file, $filesystem);
            $deleted = $filesystem->delete($file->getPath());
        } else {
            $this->beforeDelete($file);
            $deleted = unlink($file->getPath());
        }

        if ($deleted) {
            $this->afterDelete($file, $filesystem);
        }

        return $deleted;
    }

    /**
     * @param string $path
     * @param Filesystem|string $filesystem
     * @return bool
     */
    public function deleteDirectory($path, $filesystem = null)
    {
        if ($filesystem && !$filesystem instanceof Filesystem) {
            $filesystem = $this->get($filesystem);
        }
        if ($filesystem) {
            return $filesystem->deleteDir($path);
        } else {
            FileHelper::removeDirectory($path);
        }
    }



    /**
     * Returns file public url.
     *
     * @param FileInterface $file
     * @param string $filesystem
     * @param array $params
     * @return string
     */
    public function getUrl(FileInterface $file, $filesystem, $params = [])
    {
        $filesystem = $this->get($filesystem);
        $filesystem->addPlugin(new UrlPlugin());
        if ($params) {
            return Yii::$app->get('glide')->createUrl(array_merge([
                '/glide/index',
                'server' => 'local',
                'path' => $file->getPath()
            ], $params), true);
        }

        return $filesystem->getUrl($file->getPath());
    }

    /**
     * Called before file saving.
     *
     * @param FileInterface $file
     * @param Filesystem $filesystem
     * @param string $path
     */
    protected function beforeSave(FileInterface $file, Filesystem $filesystem = null, $path = null)
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
    protected function afterSave(FileInterface $file, Filesystem $filesystem = null, $path = null)
    {
        $event = new FilesystemEvent(['file' => $file, 'filesystem' => $filesystem, 'path' => $path]);
        $this->trigger(self::EVENT_AFTER_SAVE, $event);
    }

    /**
     * Called before file deleting.
     *
     * @param FileInterface $file
     * @param Filesystem $filesystem
     */
    protected function beforeDelete(FileInterface $file, Filesystem $filesystem = null)
    {
        $event = new FilesystemEvent(['file' => $file, 'filesystem' => $filesystem]);
        $this->trigger(self::EVENT_BEFORE_DELETE, $event);
    }

    /**
     * Called after file deleting.
     *
     * @param FileInterface $file
     * @param Filesystem $filesystem
     */
    protected function afterDelete(FileInterface $file, Filesystem $filesystem = null)
    {
        $event = new FilesystemEvent(['file' => $file, 'filesystem' => $filesystem]);
        $this->trigger(self::EVENT_AFTER_DELETE, $event);
    }
} 