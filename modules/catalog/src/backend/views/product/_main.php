<?php

use im\catalog\models\Brand;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model im\catalog\models\Product */
/* @var $form yii\widgets\ActiveForm */

?>

<?= $form->field($model, 'sku')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'description')->textarea(['maxlength' => true]) ?>

<?= $form->field($model, 'brand_id')->dropDownList(
    ArrayHelper::map(Brand::find()->asArray()->orderBy('name')->all(), 'id', 'name'),
    ['prompt' => '']
) ?>

<?= $form->field($model, 'price')->textInput() ?>

<?= $form->field($model, 'status')->dropDownList($model::getStatusesList()) ?>
