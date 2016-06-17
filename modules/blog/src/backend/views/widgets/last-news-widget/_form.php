<?php

use im\blog\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $widget im\blog\models\widgets\LastNewsWidget */
/* @var $form yii\widgets\ActiveForm|im\forms\components\DynamicActiveForm */

?>

<?php $form = ActiveForm::begin(['id' => 'last-news-widget-form', 'options' => ['data-pjax' => 1]]); ?>

<?= $form->field($widget, 'display_count') ?>

<?= Html::submitButton(
    Module::t('page', 'Save'),
    ['class' => $widget->isNewRecord ? 'btn btn-primary btn-large' : 'btn btn-success btn-large']
) ?>

<?php ActiveForm::end(); ?>
