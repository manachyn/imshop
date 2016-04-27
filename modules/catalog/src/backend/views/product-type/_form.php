<?php

use im\catalog\models\ProductAttribute;
use im\catalog\models\ProductOption;
use im\catalog\models\ProductType;
use im\catalog\Module;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\catalog\models\ProductType */
/* @var $form yii\widgets\ActiveForm */
$parent = $model->getParent();
?>

<div class="product-type-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parent_id')->dropDownList(
        ArrayHelper::map(ProductType::find()->asArray()->orderBy('name')->all(), 'id', 'name'),
        ['prompt' => '']
    ) ?>

    <?= $form->field($model, 'eAttributes')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(ProductAttribute::find()->asArray()->orderBy('name')->all(), 'id', 'presentation'),
        'options' => ['multiple' => true]
    ]); ?>

    <?= $form->field($model, 'options')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(ProductOption::find()->asArray()->orderBy('name')->all(), 'id', 'presentation'),
        'options' => ['multiple' => true]
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
