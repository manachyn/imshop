<?php

namespace im\filesystem\components;

use creocoder\flysystem\Filesystem;
use im\filesystem\exception\FilesystemException;
use yii\base\Component;
use yii\di\Instance;
use yii\web\UploadedFile;

/**
 * Filesystem component class
 */
class FilesystemComponent extends Component
{
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

    /**
     * Save uploaded file to the path.
     *
     * @param UploadedFile $file
     * @param $path
     * @param StorageConfig $config
     * @param int $index
     * @throws \Exception
     * @return bool whether the file was saved.
     */
    public function saveUploadedFile(UploadedFile $file, $path, StorageConfig $config = null, $index = 1)
    {
        if ($config && $config->filesystem && $filesystem = $this->get($config->filesystem)) {
            if ($file->error == UPLOAD_ERR_OK && is_uploaded_file($file->tempName)) {
                $stream = fopen($file->tempName, 'r+');
                return $filesystem->writeStream($path, $stream, ['visibility' => $config->visibility]);
            }
        } else {
            $dir = pathinfo($path, PATHINFO_DIRNAME);
            if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
                throw new FilesystemException("Can't create the directory '$dir'");
            }
            return $file->saveAs($path);
        }

        return false;
    }
} 