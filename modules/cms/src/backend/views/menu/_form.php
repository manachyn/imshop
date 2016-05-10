<?php

use im\cms\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\cms\models\Menu */
/* @var $form yii\widgets\ActiveForm */
?>

<?php $form = ActiveForm::begin(); ?>

<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'location')->dropDownList($model->getLocationsList()) ?>

<?= Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'), [
    'class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success',
    'name' => 'submit-button'
]) ?>

<?php ActiveForm::end(); ?>
