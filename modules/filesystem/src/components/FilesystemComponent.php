<?php

namespace im\filesystem\components;

use creocoder\flysystem\Filesystem;
use yii\base\Component;
use yii\di\Instance;

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
} 