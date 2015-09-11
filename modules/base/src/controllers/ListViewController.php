<?php

namespace im\base\controllers;

use im\forms\components\DynamicActiveForm;
use Yii;
use yii\web\Controller;

class ListViewController extends Controller
{
    /**
     * Creates list item instance and renders it in provided view.
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionAdd()
    {
        if ($itemClass = Yii::$app->request->post('itemClass')) {
            $model = Yii::createObject($itemClass);
            if ($itemView = Yii::$app->request->post('itemView')) {
                $viewParams = Yii::$app->request->post('viewParams', []);
                $viewParams['model'] = $model;
                if (isset($viewParams['form'])) {
                    $viewParams['form'] = new DynamicActiveForm(['config' => $viewParams['form']]);
                }
                if (isset($viewParams['widget'])) {
                    $viewParams['widget'] = (object) $viewParams['widget'];
                }
                $content = $this->renderAjax($itemView, $viewParams);
                if ($itemContainerView = Yii::$app->request->post('itemContainerView')) {
                    $content = $this->renderAjax($itemContainerView, array_merge($viewParams, ['itemContent' => $content]));
                }

                return $content;
            }
        }
    }
}