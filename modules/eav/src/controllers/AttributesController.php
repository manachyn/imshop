<?php

namespace im\eav\controllers;

use im\eav\models\Attribute;
use im\eav\models\AttributeValue;
use yii\web\Controller;
use Yii;

class AttributesController extends Controller
{
    public function actionFields()
    {
        if (($attributes = Yii::$app->request->post('attributes')) && ($form = Yii::$app->request->post('form'))) {
            $attributes = Attribute::findAll(['id' => $attributes]);
            $values = [];
            foreach ($attributes as $attribute) {
                $value = AttributeValue::getInstance();
                $value->setEAttribute($attribute);
                $values[$attribute->getName()] = $value;
            }
            if (Yii::$app->request->isAjax) {
                return $this->renderAjax('_fields', [
                    'attributes' => $values,
                    'form' => $form
                ]);
            }
        }
    }
}
