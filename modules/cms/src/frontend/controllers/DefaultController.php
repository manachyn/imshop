<?php

namespace im\cms\frontend\controllers;

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
