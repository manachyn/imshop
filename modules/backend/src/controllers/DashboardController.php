<?php

namespace im\backend\controllers;

use im\base\controllers\BackendController;

/**
 * Class DashboardController
 * @package im\backend\controllers
 */
class DashboardController extends BackendController
{
    /**
     * Display backend dashboard.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
