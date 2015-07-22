<?php

namespace im\filesystem\components;

interface FileInterface
{
    public function setPath($path);

    /**
     * @return string
     */
    public function getPath();
} 