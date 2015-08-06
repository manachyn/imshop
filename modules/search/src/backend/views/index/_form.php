<?php

use im\search\backend\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\search\models\Index */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="index-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'server')->dropDownList(
        $model::getServersList(),
        ['prompt' => '']
    ) ?>

    <?= $form->field($model, 'entity_type')->dropDownList(
        $model::getEntityTypesList(),
        ['prompt' => '']
    ) ?>

    <?=$form->field($model, 'status')->dropDownList($model::getStatusesList()) ?>

    <?= Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>

</div>
