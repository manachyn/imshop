<?php

use im\blog\Module;
use im\forms\components\ContentBlock;
use im\forms\components\FieldSet;
use im\forms\components\Tab;
use im\forms\components\TabSet;
use im\wysiwyg\WysiwygEditor;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\blog\models\Article */
/* @var $form yii\widgets\ActiveForm */

$wysiwygConfig = Yii::$app->get('config')->get('wysiwyg.*');

?>

<?php $form = ActiveForm::begin([
    'id' => 'news-form',
    'fieldClass' => 'im\forms\widgets\ActiveField',
    'options' => ['enctype' => 'multipart/form-data']
]); ?>

<?= new FieldSet('article', [
    new TabSet('tabs', [
        new Tab('main', Module::t('article', 'Main'), [
            $form->field($model, 'title')->textInput(['maxlength' => true]),
            $form->field($model, 'slug')->textInput(['maxlength' => true]),
            $form->field($model, 'content')->widget(WysiwygEditor::className(), [
                'options' => ['rows' => 6],
                'editor' => $wysiwygConfig['wysiwyg.editor'],
                'preset' => $wysiwygConfig['wysiwyg.preset'],
                'fileManagerRoute' => ['/elfinder/manager']
            ]),
            $form->field($model, 'status')->dropDownList($model::getStatusesList())
        ]),
        new Tab('files', Module::t('article', 'Files'), [
            $form->field($model, 'uploadedImage')->fileInput(['accept' => 'image/*']),
            new ContentBlock('image', $this->render('_image', [
                'model' => $model,
                'form' => $form
            ])),
            $form->field($model, 'uploadedVideo')->fileInput(['accept' => 'video/*']),
            new ContentBlock('video', $this->render('_video', [
                'model' => $model,
                'form' => $form
            ]))
        ])
    ])
]) ?>

<?= Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'), [
    'class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success',
    'name' => 'submit-button'
]) ?>

<?php ActiveForm::end(); ?>