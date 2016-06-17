<?php

/**
 * @var $this yii\web\View
 * @var $model im\rbac\models\Assignment
 */

use im\rbac\Module;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

?>

<?php $form = ActiveForm::begin(['enableClientValidation' => false, 'enableAjaxValidation' => false]) ?>

<?= $form->field($model, 'user_id')->hiddenInput()->label(false) ?>

<?= $form->field($model, 'roles')->widget(Select2::className(), [
    'data' => $model->getAvailableRoles(),
    'options' => [
        'prompt' => '',
        'multiple' => true
    ],
]) ?>

<?= Html::submitButton(Module::t('module', 'Update assignments'), ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end() ?>