<?php

use im\search\backend\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\search\models\FacetTerm */
/* @var $form yii\widgets\ActiveForm|im\forms\components\DynamicActiveForm */
/* @var $fieldConfig array */

$fieldConfig = isset($fieldConfig) ? $fieldConfig : [];
?>

<?php if (!isset($form)) { $form = ActiveForm::begin(); } ?>

<div class="form-inline">
    <?= $form->field($model, 'term', $fieldConfig)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'display', $fieldConfig)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort', $fieldConfig)->hiddenInput(['data-field' => 'sort'])->label(false) ?>

    <?= $form->field($model, 'type', $fieldConfig)->hiddenInput()->label(false) ?>

    <?= $form->field($model, 'id', $fieldConfig)->hiddenInput()->label(false) ?>
</div>

<?php if (!isset($form)) {

    echo Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']);

    ActiveForm::end();
} ?>
