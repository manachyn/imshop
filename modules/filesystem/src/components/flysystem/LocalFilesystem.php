<?php

namespace im\filesystem\components\flysystem;

use im\filesystem\components\flysystem\adapters\Local;

class LocalFilesystem extends \creocoder\flysystem\LocalFilesystem
{
    /**
     * @return Local
     */
    protected function prepareAdapter()
    {
        return new Local($this->path);
    }
} 