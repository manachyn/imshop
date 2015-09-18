<?php

use im\cms\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $widget im\cms\models\ContentWidget */
/* @var $form yii\widgets\ActiveForm|im\forms\components\DynamicActiveForm */

?>

<?php $form = ActiveForm::begin(['id' => 'content-widget-form', 'options' => ['data-pjax' => 1]]); ?>

<?= $form->field($widget, 'banner_id')->textInput() ?>

<?= Html::submitButton(
    Module::t('page', 'Save'),
    ['class' => $widget->isNewRecord ? 'btn btn-primary btn-large' : 'btn btn-success btn-large']
) ?>

<?php ActiveForm::end(); ?>
