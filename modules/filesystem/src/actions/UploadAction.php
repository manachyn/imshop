<?php

namespace im\filesystem\actions;

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
     * @var string path
     */
    public $path = 'uploads';

    /**
     * @var string
     */
    public $fileParam = 'files';

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
        'path' => 'path',
        'filesystem' => 'filesystem',
        'url' => 'url',
        'thumbnailUrl' => 'thumbnailUrl',
        'deleteUrl' => 'deleteUrl',
        'error' => 'error'
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
        Yii::$app->response->format = $this->responseFormat;
        if ($fileParam = Yii::$app->request->get('fileparam')) {
            $this->fileParam = $fileParam;
        }
    }

    public function run()
    {
        $response = [];
        $uploadedFiles = UploadedFile::getInstancesByName($this->fileParam);
        foreach ($uploadedFiles as $uploadedFile) {

            $responseFile = new \stdClass();
            $responseFile->{$this->responseParamsMap['name']} = $uploadedFile->name;
            $responseFile->{$this->responseParamsMap['type']} = $uploadedFile->type;
            $responseFile->{$this->responseParamsMap['size']} = $uploadedFile->size;

            if (!$uploadedFile->hasError) {
                $model = DynamicModel::validateData(['file' => $uploadedFile], $this->validationRules);
                if (!$model->hasErrors()) {
                    $filesystemComponent = $this->getFilesystemComponent();
                    $filesystem = $this->getFilesystem();
                    /** @var FileInterface $fileClass */
                    $fileClass = $this->fileClass;
                    $file = $fileClass::getInstanceFromUploadedFile($uploadedFile);
                    //$path = $this->path . DIRECTORY_SEPARATOR . $uploadedFile->name;
                    $path = $this->path . DIRECTORY_SEPARATOR . date('dmYHis') . '-' . $file->getBasename() . '.' . $uploadedFile->extension;
                    $path = $filesystemComponent->saveFile($file, $filesystem, $path);
                    if ($path) {
                        $file->setPath($path);
                        $responseFile->{$this->responseParamsMap['filesystem']} = $filesystem;
                        $responseFile->{$this->responseParamsMap['path']} = $path;
                        $responseFile->{$this->responseParamsMap['name']} = $uploadedFile->name;
                        $responseFile->{$this->responseParamsMap['url']} = $filesystemComponent->getUrl($file, $filesystem);
                        $responseFile->{$this->responseParamsMap['deleteUrl']} = Url::to([$this->deleteRoute, 'path' => $path]);
                    } else {
                        $responseFile->{$this->responseParamsMap['error']} = 'Saving error';
                    }
                } else {
                    $responseFile->{$this->responseParamsMap['error']} = $model->errors;
                }
            } else {
                $responseFile->{$this->responseParamsMap['error']} = $uploadedFile->error;
            }

            $response['files'][] = $responseFile;
        }

        return $this->multiple ? $response : array_shift($response);
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