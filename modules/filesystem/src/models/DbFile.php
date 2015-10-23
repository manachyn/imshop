<?php

namespace im\filesystem\models;

use im\filesystem\components\FileInterface;
use im\filesystem\components\FilesystemComponent;
use im\filesystem\exception\UploadException;
use im\filesystem\Module;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * File model class.
 *
 * @property integer $id
 * @property string $filesystem
 * @property string $path
 * @property string $title
 * @property integer $size
 * @property string $mime_type
 * @property integer $created_at
 * @property integer $updated_at
 */
class DbFile extends ActiveRecord implements FileInterface
{
    /**
     * @var FilesystemComponent
     */
    protected $filesystemComponent;

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
            [['path'], 'required'],
            [['id', 'title', 'filesystem', 'size', 'mime_type', 'filename', 'basename'], 'safe'],
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
            'created_at' => Module::t('file', 'Created at'),
            'updated_at' => Module::t('file', 'Updated at')
        ];
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return $this->getUrl();
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
    public function getUrl($params = [])
    {
        if ($this->getFilesystemName()) {
            return $this->getFilesystemComponent()->getUrl($this, $this->getFilesystemName(), $params);
        } else {
            return '';
        }
    }

    /**
     * @inheritdoc
     */
    public function setFilename($filename)
    {
        $pathParts = pathinfo($this->getPath());
        if ($pathParts['filename'] !== $filename) {
            $pathParts['filename'] = $filename;
            $this->setPath($pathParts['dirname'] . DIRECTORY_SEPARATOR . $pathParts['filename'] . '.' . $pathParts['extension']);
        }
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
        $pathParts = pathinfo($this->getPath());
        if ($pathParts['basename'] !== $basename) {
            $pathParts['basename'] = $basename;
            $this->setPath($pathParts['dirname'] . DIRECTORY_SEPARATOR . $pathParts['basename']);
        }
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

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($this->isAttributeChanged('path') && $oldPath = $this->getOldAttribute('path')) {
            $this->getFilesystemComponent()->get($this->getFilesystemName())->rename($oldPath, $this->getPath());
        }
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function afterDelete()
    {
        $this->getFilesystemComponent()->deleteFile($this);
        parent::afterDelete();
    }

    /**
     * @return FilesystemComponent
     */
    protected function getFilesystemComponent()
    {
        if ($this->filesystemComponent) {
            return $this->filesystemComponent;
        } else {
            return $this->filesystemComponent = Yii::$app->get('filesystem');
        }
    }
}