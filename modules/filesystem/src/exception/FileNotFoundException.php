<?php

namespace im\filesystem\exception;

class FileNotFoundException extends FilesystemException
{
    public function __construct($path)
    {
        parent::__construct(sprintf('The file "%s" does not exist', $path));
    }
}
