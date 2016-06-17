<?php

/**
 * @var $this yii\web\View
 * @var $model im\rbac\models\Role
 */

use im\rbac\Module;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin(['enableClientValidation' => false, 'enableAjaxValidation' => true]) ?>

<?= $form->field($model, 'name') ?>

<?= $form->field($model, 'description') ?>

<?= $form->field($model, 'ruleName')->widget(Select2::className(), [
    'data' => $model->getAvailableRules(),
    'options' => [
        'prompt' => ''
    ],
]) ?>

<?= $form->field($model, 'childrenRoles')->widget(Select2::className(), [
    'data' => $model->getAvailableRoles(),
    'options' => [
        'prompt' => '',
        'multiple' => true
    ],
]) ?>

<?= $form->field($model, 'childrenPermissions')->widget(Select2::className(), [
    'data' => $model->getAvailablePermissions(),
    'options' => [
        'prompt' => '',
        'multiple' => true
    ],
]) ?>

<?= Html::submitButton(Module::t('module', 'Save'), ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end() ?>