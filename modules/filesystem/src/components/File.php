<?php

namespace im\filesystem\components;

use im\filesystem\exception\FileNotFoundException;

class File extends \SplFileInfo
{
    public $filesystem;

    /**
     * Constructs a new file from the given path.
     *
     * @param string $path the path to the file
     * @param boolean $checkPath whether to check the path or not
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
     * Returns the mime type of the file.
     *
     * It uses finfo(), mime_content_type() and the system binary "file" (in this order),
     * depending on which of those are available.
     *
     * @return string|null
     */
    public function getMimeType()
    {
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $this->getRealPath());
            finfo_close($finfo);
            return $mimeType;
        } elseif (function_exists('mime_content_type')) {
            return $mimeType = mime_content_type($this->getRealPath());
        } elseif (function_exists('exif_imagetype')) {
            return $mimeType = image_type_to_mime_type(exif_imagetype($this->getRealPath()));
        }

        return null;
    }
}