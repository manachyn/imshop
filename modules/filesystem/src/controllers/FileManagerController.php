<?php

namespace im\filesystem\controllers;

use app\modules\backend\components\Controller;

class FileManagerController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
} 