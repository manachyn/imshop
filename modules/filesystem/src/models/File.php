<?php

namespace im\filesystem\models;

use creocoder\flysystem\Filesystem;
use im\filesystem\components\FileInterface;
use im\filesystem\exception\FileNotFoundException;
use im\filesystem\exception\UploadException;
use yii\base\Model;
use Yii;
use yii\web\UploadedFile;

class File extends Model implements FileInterface
{
    /**
     * @var Filesystem|string the filesystem object or the application component ID of the filesystem object.
     */
    private $_filesystem;

    protected $path;

    protected $mimeType;

    protected $size;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['path', 'required']
        ];
    }

    /**
     * @param Filesystem|string $filesystem
     */
    public function setFilesystem($filesystem)
    {
        $this->_filesystem = $filesystem;
    }

    /**
     * @return Filesystem|null
     */
    public function getFilesystem()
    {
        if (!$this->_filesystem instanceof Filesystem) {
            $this->_filesystem = Yii::$app->get('filesystem')->get($this->_filesystem);
        }

        return $this->_filesystem;
    }

    /**
     * Creates file instances from array of data.
     *
     * @param array $data
     * @return static[]
     */
    public static function getInstances($data)
    {
        foreach ($data as $key => $item) {
            $data[$key] = static::getInstance($item);
        }

        return $data;
    }

    /**
     * Creates file instance from data.
     *
     * @param array $data
     * @return static
     */
    public static function getInstance($data)
    {
        return new static($data);
    }

    /**
     * @inheritdoc
     */
    public static function getInstanceFromUploadedFile(UploadedFile $uploadedFile)
    {
        if ($uploadedFile->hasError) {
            throw new UploadException("File upload error '{$uploadedFile->error}'");
        }

        return new static([
            'path' => $uploadedFile->tempName,
            'mimeType' => $uploadedFile->type,
            'size' => $uploadedFile->size
        ]);
    }

    public function __sleep()
    {
        return array('path', '_filesystem');
    }

    public function __toString()
    {
        return $this->path;
    }

    /**
     * @inheritdoc
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @inheritdoc
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @inheritdoc
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @inheritdoc
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
    }

    /**
     * @inheritdoc
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @inheritdoc
     */
    public function setSize($size)
    {
        $this->size = $size;
    }





//    /**
//     * Constructs a new file from the given path.
//     *
//     * @param string $path the path to the file
//     * @param boolean $checkPath whether to check the path or not
//     * @throws FileNotFoundException If the given path is not a file
//     */
//    public function __construct($path, $checkPath = true)
//    {
//        if ($checkPath && !is_file($path)) {
//            throw new FileNotFoundException($path);
//        }
//        parent::__construct($path);
//    }
//
//    /**
//     * Returns the mime type of the file.
//     *
//     * It uses finfo(), mime_content_type() and the system binary "file" (in this order),
//     * depending on which of those are available.
//     *
//     * @return string|null
//     */
//    public function getMimeType()
//    {
//        if (function_exists('finfo_open')) {
//            $finfo = finfo_open(FILEINFO_MIME_TYPE);
//            $mimeType = finfo_file($finfo, $this->getRealPath());
//            finfo_close($finfo);
//            return $mimeType;
//        } elseif (function_exists('mime_content_type')) {
//            return $mimeType = mime_content_type($this->getRealPath());
//        } elseif (function_exists('exif_imagetype')) {
//            return $mimeType = image_type_to_mime_type(exif_imagetype($this->getRealPath()));
//        }
//
//        return null;
//    }

    /**
     * @inheritdoc
     */
    public function setFilename($filename)
    {
        // TODO: Implement
    }

    /**
     * @inheritdoc
     */
    public function getFilename()
    {
        return pathinfo($this->path, PATHINFO_FILENAME);
    }

    /**
     * @inheritdoc
     */
    public function getExtension()
    {
        return pathinfo($this->path, PATHINFO_EXTENSION);
    }

    /**
     * @inheritdoc
     */
    public function setBasename($basename)
    {
        // TODO: Implement setBasename() method.
    }

    /**
     * @inheritdoc
     */
    public function getBasename($suffix = null)
    {
        return pathinfo($this->path, PATHINFO_BASENAME);
    }
}