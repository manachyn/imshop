<?php

namespace im\filesystem\events;

use yii\base\Event;

class FilesystemEvent extends Event
{
    /**
     * @var \creocoder\flysystem\Filesystem
     */
    public $filesystem;

    /**
     * @var \im\filesystem\components\FileInterface
     */
    public $file;

    /**
     * @var string
     */
    public $path;
}
