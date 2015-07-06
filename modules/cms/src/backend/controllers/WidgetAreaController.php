<?php

namespace im\cms\backend\controllers;

use im\cms\components\layout\LayoutManager;
use im\controllers\BackendController;
use im\forms\components\DynamicActiveForm;
use yii\base\InvalidParamException;
use Yii;

/**
 * Class WidgetAreaController implements base operations for widget area
 * @package im\cms\backend\controllers
 */
class WidgetAreaController extends BackendController
{
    /**
     * Return html presentation of selected widget (form for editable one).
     *
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionAddWidget()
    {
        if ($type = Yii::$app->request->post('type')) {
            /** @var LayoutManager $layoutManager */
            $layoutManager = Yii::$app->get('layoutManager');
            $widget = $layoutManager->getWidgetInstance($type);

            if ($widget == null) {
                throw new InvalidParamException("Can't find widget by type '$type'");
            }

            if (($form = Yii::$app->request->post('form')) && ($index = Yii::$app->request->post('index'))) {
                $form = new DynamicActiveForm(['config' => $form]);
                if (Yii::$app->request->isAjax) {
                    return $this->renderAjax('_selected_widget', ['widget' => $widget, 'form' => $form, 'index' => $index]);
                }
            }
        }
    }
}
