<?php

use im\catalog\Module;
use im\eav\models\Attribute;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\eav\models\Value */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="value-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'attribute_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Attribute::find()->asArray()->orderBy('name')->all(), 'id', 'presentation'),
        'options' => ['prompt' => '']
    ]); ?>

    <?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'presentation')->textInput(['maxlength' => true]) ?>

    <?= Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>

</div>
