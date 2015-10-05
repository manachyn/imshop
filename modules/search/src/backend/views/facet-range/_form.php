<?php

use im\search\backend\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\search\models\FacetRange */
/* @var $form yii\widgets\ActiveForm|im\forms\components\DynamicActiveForm */
/* @var $fieldConfig array */

$fieldConfig = isset($fieldConfig) ? $fieldConfig : [];
?>

<?php if (!isset($form)) { $form = ActiveForm::begin(); } ?>

<div class="form-inline">

    <?= $form->field($model, 'lower_bound', array_merge($fieldConfig, [
        'template' => "{label}\n<div class=\"input-group\"><span class=\"input-group-addon\">" . $form->field($model, 'include_lower_bound', array_merge($fieldConfig, [
                'template' => "{input}"
            ]))->checkbox(['label' => false]) . "</span>{input}</div>\n{hint}\n{error}"
    ]))->textInput(['maxlength' => true])->label(false) ?>

    <span><strong>to</strong></span>

    <?= $form->field($model, 'upper_bound', array_merge($fieldConfig, [
        'template' => "{label}\n<div class=\"input-group\">{input}\n<span class=\"input-group-addon\">" . $form->field($model, 'include_upper_bound', array_merge($fieldConfig, [
                'template' => "{input}"
            ]))->checkbox(['label' => false]) . "</span></div>\n{hint}\n{error}"
    ]))->textInput(['maxlength' => true])->label(false) ?>

    <?= $form->field($model, 'display', $fieldConfig)->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sort', $fieldConfig)->hiddenInput(['data-field' => 'sort'])->label(false) ?>

    <?= $form->field($model, 'id', $fieldConfig)->hiddenInput()->label(false) ?>
</div>

<?php if (!isset($form)) {

    echo Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']);

    ActiveForm::end();
} ?>
