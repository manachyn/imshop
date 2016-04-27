<?php

use im\catalog\Module;
use im\forms\components\FieldSet;
use im\forms\components\Tab;
use im\forms\components\TabSet;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\catalog\models\Category */
/* @var $form yii\widgets\ActiveForm */

?>

<?php $form = ActiveForm::begin(['id' => 'category-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>

<?= new FieldSet('category', [
    new TabSet('tabs', [
        new Tab('main', Module::t('category', 'Main'), [
            $form->field($model, 'name')->textInput(['maxlength' => true]),
            $form->field($model, 'slug')->textInput(['maxlength' => true]),
            $form->field($model, 'description')->textarea(),
            $form->field($model, 'status')->dropDownList($model::getStatusesList())
        ])
    ])
]) ?>

<?= Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'),
    ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>


