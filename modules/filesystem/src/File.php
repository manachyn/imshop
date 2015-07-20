<?php

namespace im\filesystem;

use im\filesystem\exception\FileNotFoundException;

class File extends \SplFileInfo
{
    /**
     * Constructs a new file from the given path.
     * @param $path The path to the file
     * @param bool $checkPath Whether to check the path or not
     * @throws FileNotFoundException If the given path is not a file
     */
    public function __construct($path, $checkPath = true)
    {
        if ($checkPath && !is_file($path)) {
            throw new FileNotFoundException($path);
        }

        parent::__construct($path);
    }

    /**
     * Returns the extension of the file.
     * \SplFileInfo::getExtension() is not available before PHP 5.3.6
     * @return string The extension
     */
    public function getExtension()
    {
        return pathinfo($this->getBasename(), PATHINFO_EXTENSION);
    }

    public function getMimeType() {

        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $this->getRealPath());
            finfo_close($finfo);
            return $mimeType;
        }
        elseif (function_exists('mime_content_type'))
        {
            return $mimeType = mime_content_type($this->getRealPath());
        }

        elseif (function_exists('exif_imagetype'))
        {
            return $mimeType = image_type_to_mime_type(exif_imagetype($this->getRealPath()));
        }

        return false;
    }
}