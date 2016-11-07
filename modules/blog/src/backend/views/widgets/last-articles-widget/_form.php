<?php

use im\blog\models\ArticleCategory;
use im\blog\Module;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\blog\widgets\LastArticlesWidget */
/* @var $form yii\widgets\ActiveForm|im\forms\components\DynamicActiveForm */
/* @var $fieldConfig array */

$fieldConfig = isset($fieldConfig) ? $fieldConfig : [];
?>

<?php if (!isset($form)) {
    $form = ActiveForm::begin(['id' => 'last-articles-widget-form', 'options' => ['data-pjax' => 1]]);
} ?>

<?= $form->field($model, 'title', $fieldConfig) ?>

<?= $form->field($model, 'category_id', $fieldConfig)->dropDownList(
    ArrayHelper::map(ArticleCategory::find()->asArray()->orderBy('name')->all(), 'id', 'name'),
    ['prompt' => '']
) ?>

<?= $form->field($model, 'display_count', $fieldConfig) ?>

<?= $form->field($model, 'list_url', $fieldConfig) ?>

<?php if (!isset($form)) {

    echo Html::submitButton(Module::t('module', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']);

    ActiveForm::end();

} ?>