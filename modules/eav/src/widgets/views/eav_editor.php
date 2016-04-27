<?php

use im\eav\models\Attribute;
use im\eav\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $attributes im\eav\models\AttributeValue[] */
/* @var $form ActiveForm|array */

?>

<div data-cont="fields">
    <?= $this->render('@im/eav/backend/views/attribute/_fields', ['attributes' => $attributes, 'form' => $form]) ?>
</div>

<div class="form-inline">
    <?= Html::dropDownList('attributes', null,
        ArrayHelper::map(Attribute::find()->asArray()->orderBy('name')->all(), 'id', 'presentation'),
        ['class' => 'form-control', 'data-field' => 'attributes', 'multiple' => true]
    ) ?>
    <?= Html::button(Module::t('attribute', 'Add attribute'), ['class' => 'btn btn-primary', 'data-action' => 'add']) ?>
</div>

<div data-cont="temp"></div>