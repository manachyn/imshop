<?php

namespace im\filesystem\components;

use yii\web\UploadedFile;

interface FileInterface
{
    /**
     * Sets the file path.
     *
     * @param string $path
     */
    public function setPath($path);

    /**
     * Gets the file path.
     *
     * @return string the path to the file.
     */
    public function getPath();

    /**
     * Sets the file name.
     *
     * @param string $filename
     */
    public function setFilename($filename);

    /**
     * Gets the file name.
     *
     * @return string the filename.
     */
    public function getFilename();

    /**
     * Gets the file extension.
     *
     * @return string a string containing the file extension, or an empty string if the file has no extension.
     */
    public function getExtension();

    /**
     * Sets the file base name.
     *
     * @param string $basename
     */
    public function setBasename($basename);

    /**
     * Gets the base name of the file.
     *
     * @param string $suffix optional suffix to omit from the base name returned.
     * @return string the base name without path information.
     */
    public function getBasename($suffix = null);

    /**
     * Sets the file mime type.
     *
     * @param string $mineType
     */
    public function setMimeType($mineType);

    /**
     * Gets the file mime type.
     *
     * @return string the mine type.
     */
    public function getMimeType();

    /**
     * Sets the file size.
     *
     * @param int $size
     */
    public function setSize($size);

    /**
     * Gets the file size.
     *
     * @return int the size.
     */
    public function getSize();

    /**
     * Creates file instance from uploaded file.
     *
     * @param UploadedFile $uploadedFile
     * @return static
     */
    public static function getInstanceFromUploadedFile(UploadedFile $uploadedFile);
} 