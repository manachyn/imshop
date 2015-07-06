<?php

namespace im\backend\controllers;

use im\backend\components\Controller;

class DefaultController extends Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}
