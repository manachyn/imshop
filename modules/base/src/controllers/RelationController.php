<?php

namespace im\base\controllers;

use im\forms\components\DynamicActiveForm;
use Yii;
use yii\web\Controller;

/**
 * Class RelationController
 * @package im\base\controllers
 */
class RelationController extends Controller
{
    /**
     * Creates model class and renders it in provided view.
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionAdd()
    {
        if ($modelClass = Yii::$app->request->post('modelClass')) {
            $model = Yii::createObject($modelClass);
            if ($view = Yii::$app->request->post('view')) {
                $viewParams = Yii::$app->request->post('viewParams', []);
                $viewParams['model'] = $model;
                if (isset($viewParams['form'])) {
                    $viewParams['form'] = new DynamicActiveForm(['config' => $viewParams['form']]);
                }
                return $this->renderAjax($view, $viewParams);
            }
        }
    }
}