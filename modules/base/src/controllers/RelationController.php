<?php

namespace im\base\controllers;

use im\forms\components\DynamicActiveForm;
use Yii;
use yii\web\Controller;

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
            if ($modelView = Yii::$app->request->post('modelView')) {
                $params = ['model' => $model];
                if ($form = Yii::$app->request->post('form')) {
                    $params['form'] = $form = new DynamicActiveForm(['config' => $form]);
                    if ($index = Yii::$app->request->post('index')) {
                        $params['fieldConfig'] = ['tabularIndex' => $index];
                        $params['index'] = $index;
                    };
                }
                return $this->renderAjax($modelView, $params);
            }
        }
    }
}