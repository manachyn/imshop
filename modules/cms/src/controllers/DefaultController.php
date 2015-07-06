<?php

namespace im\cms\controllers;

use yii\web\Controller;

class DefaultController extends Controller
{
    /**
     * Site "Home" page.
     */
    public function actionIndex()
    {
        $this->layout = '//home';

        return $this->render('index');
    }
}
