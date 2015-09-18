<?php

use im\cms\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\catalog\models\widgets\ProductCategoriesWidget */
/* @var $form yii\widgets\ActiveForm|im\forms\components\DynamicActiveForm */
/* @var $fieldConfig array */

$fieldConfig = isset($fieldConfig) ? $fieldConfig : [];
?>

<?php if (!isset($form)) {
    $form = ActiveForm::begin(['id' => 'product-categories-widget', 'options' => ['data-pjax' => 1]]);
} ?>

<?= $form->field($model, 'depth', $fieldConfig)->textInput() ?>

<?php if (!isset($form)) {

echo Html::submitButton(Module::t('module', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']);

ActiveForm::end(); } ?>