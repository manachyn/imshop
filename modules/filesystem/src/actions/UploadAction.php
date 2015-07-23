<?php

namespace im\filesystem\controllers;

use im\filesystem\components\FileInterface;
use im\filesystem\components\FilesystemComponent;
use im\filesystem\models\File;
use Yii;
use yii\base\Action;
use yii\base\DynamicModel;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\UploadedFile;

class UploadAction extends Action
{
    /**
     * @var string filesystem component request param name
     */
    public $filesystemComponentParam = 'filesystemComponent';

    /**
     * @var string filesystem request param name
     */
    public $filesystemParam = 'filesystem';

    /**
     * @var string filesystem component name
     */
    public $filesystemComponent = 'filesystem';

    /**
     * @var string filesystem component name
     */
    public $filesystem = 'local';

    /**
     * @var string
     */
    public $fileParam = 'file';

    /**
     * @var bool
     */
    public $multiple = true;

    /**
     * @var array
     */
    public $validationRules;

    /**
     * @var string
     */
    public $fileClass = 'im\filesystem\models\File';

    /**
     * @var string
     */
    public $responseFormat = Response::FORMAT_JSON;

    /**
     * @var array
     */
    public $responseParamsMap = [
        'name' => 'name',
        'type' => 'type',
        'size' => 'size',
        'url' => 'url',
        'deleteUrl' => 'deleteUrl'
    ];

    /**
     * @var string
     */
    public $deleteRoute = 'delete';

    /**
     * @inheritdoc
     */
    public function init()
    {

    }

    public function run()
    {
        $uploadedFiles = UploadedFile::getInstancesByName($this->fileParam);
        foreach ($uploadedFiles as $uploadedFile) {

            $responseFile = new \stdClass();
            $responseFile->{$this->responseParamsMap['name']} = $uploadedFile->name;
            $responseFile->{$this->responseParamsMap['type']} = $uploadedFile->type;
            $responseFile->{$this->responseParamsMap['size']} = $uploadedFile->size;

            if ($uploadedFile->error == UPLOAD_ERR_OK && is_uploaded_file($uploadedFile->tempName)) {
                $model = DynamicModel::validateData(['file' => $uploadedFile], $this->validationRules);
                if (!$model->hasErrors()) {
                    $filesystemComponent = $this->getFilesystemComponent();
                    $filesystem = $this->getFilesystem();
                    /** @var FileInterface $fileClass */
                    $fileClass = $this->$fileClass;
                    $file = $fileClass::getInstanceFromUploadedFile($uploadedFile);
                    $path = $filesystemComponent->saveFile($file, $filesystem);
                    if ($path) {
                        $responseFile->{$this->responseParamsMap['name']} = $file->getFilename();
                        $responseFile->{$this->responseParamsMap['url']} = $file->getFilename();
                        $responseFile->{$this->responseParamsMap['deleteUrl']} = Url::to([$this->deleteRoute, 'path' => $path]);
                    }
                } else {

                }
            }

            $result['files'][] = $responseFile;
        }
    }

    /**
     * Return filesystem component for action.
     *
     * @return \im\filesystem\components\FilesystemComponent
     * @throws \yii\base\InvalidConfigException
     */
    protected function getFilesystemComponent()
    {
        $name = Yii::$app->request->get($this->filesystemComponentParam, $this->filesystemComponent);

        return Yii::$app->get($name);
    }

    /**
     * Returns filesystem name for action.
     *
     * @return string
     */
    protected function getFilesystem()
    {
        return Yii::$app->request->get($this->filesystemParam, $this->filesystem);
    }
}