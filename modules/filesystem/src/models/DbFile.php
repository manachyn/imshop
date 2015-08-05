<?php

namespace im\filesystem\models;

use im\filesystem\components\FileInterface;
use im\filesystem\exception\UploadException;
use im\filesystem\Module;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * Class File
 *
 * @property integer $id
 * @property string $filesystem
 * @property string $path
 * @property string $title
 * @property integer $size
 * @property string $mime_type
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @package im\filesystem\exception
 */
class DbFile extends ActiveRecord implements FileInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%files}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['path', 'title'], 'required'],
            [['title', 'filesystem', 'size', 'mime_type'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('file', 'ID'),
            'filesystem' => Module::t('file', 'Filesystem'),
            'path' => Module::t('file', 'Path'),
            'size' => Module::t('file', 'Size'),
            'mime_type' => Module::t('file', 'Mime type'),
            'created_at' => Module::t('user', 'Created at'),
            'updated_at' => Module::t('user', 'Updated at')
        ];
    }

    public function __toString()
    {
        return $this->path;
    }

    /**
     * @inheritdoc
     */
    public function setFilesystemName($filesystemName)
    {
        $this->filesystem = $filesystemName;
    }

    /**
     * @inheritdoc
     */
    public function getFilesystemName()
    {
        return $this->filesystem;
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
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @inheritdoc
     */
    public function setFilename($filename)
    {
        // TODO: Implement setFilename() method.
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

    /**
     * @inheritdoc
     */
    public function setMimeType($mimeType)
    {
        $this->mime_type = $mimeType;
    }

    /**
     * @inheritdoc
     */
    public function getMimeType()
    {
        return $this->mime_type;
    }

    /**
     * @inheritdoc
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @inheritdoc
     */
    public function getSize()
    {
        return $this->size;
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
            'mime_type' => $uploadedFile->type,
            'size' => $uploadedFile->size
        ]);
    }
}