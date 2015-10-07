<?php

use im\search\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\search\models\Index */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="index-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(
        $model::getTypesList(),
        ['prompt' => '']
    ) ?>

    <?= $form->field($model, 'service')->dropDownList(
        $model::getServicesList(),
        ['prompt' => '']
    ) ?>

    <?=$form->field($model, 'status')->dropDownList($model::getStatusesList()) ?>

    <?= Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>

</div>
