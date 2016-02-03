<?php

namespace im\filesystem\rest\controllers;

use Yii;
use yii\rest\Controller;

/**
 * Class FilesystemController
 * @package im\filesystem\rest\controllers
 */
class FilesystemController extends Controller
{
    /**
     * @var \im\filesystem\components\FilesystemComponent
     */
    protected $filesystemComponent;

    public function actionIndex()
    {
        return array_map(function($name) { return ['name' => $name]; }, array_keys($this->getFilesystemComponent()->getFilesystems()));
    }

    /**
     * @return \im\filesystem\components\FilesystemComponent
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