<?php

use im\cms\Module;
use im\wysiwyg\WysiwygEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\cms\models\widgets\ContentWidget */
/* @var $form yii\widgets\ActiveForm|im\forms\components\DynamicActiveForm */
/* @var $fieldConfig array */

$fieldConfig = isset($fieldConfig) ? $fieldConfig : [];
$wysiwygConfig = Yii::$app->get('config')->get('wysiwyg.*');

?>

<?php if (!isset($form)) {
    $form = ActiveForm::begin(['id' => 'content-widget-form', 'options' => ['data-pjax' => 1]]);
} ?>

<?= $form->field($model, 'title', $fieldConfig) ?>

<?= $form->field($model, 'content', $fieldConfig)->widget(WysiwygEditor::className(), [
    'options' => ['rows' => 6],
    'editor' => $wysiwygConfig['wysiwyg.editor'],
    'preset' => $wysiwygConfig['wysiwyg.preset'],
    'fileManagerRoute' => ['/elfinder/manager']
]) ?>

<?php if (!isset($form)) {

echo Html::submitButton(Module::t('module', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']);

ActiveForm::end(); } ?>