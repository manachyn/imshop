<?php

namespace im\filesystem\controllers;

use im\base\controllers\BackendController;

class FileManagerController extends BackendController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
} 