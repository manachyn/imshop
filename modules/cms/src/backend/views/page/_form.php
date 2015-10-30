<?php

use dosamigos\ckeditor\CKEditor;
use im\cms\Module;
use im\forms\components\FieldSet;
use im\forms\components\Tab;
use im\forms\components\TabSet;
use yii\helpers\Html;
use vova07\imperavi\Widget as Imperavi;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model im\cms\models\Page */
/* @var $form yii\widgets\ActiveForm */

?>

<?php Pjax::begin(['id' => 'page-form']) ?>

<?php $form = ActiveForm::begin(['id' => 'batch-update-form', 'options' => ['data-pjax' => 1]]); ?>

<?= new FieldSet('category', [
    new TabSet('tabs', [
        new Tab('main', Module::t('category', 'Main'), [
            $model->isNewRecord ? $form->field($model, 'type')->dropDownList($model::getTypesList(), ['data-field' => 'type']) : null,
            $form->field($model, 'title')->textInput(['maxlength' => true]),
            $form->field($model, 'slug')->textInput(['maxlength' => true]),
//            $form->field($model, 'content')->widget(
//                Imperavi::className(),
//                [
//                    'settings' => [
//                        'minHeight' => 300,
////                    'imageGetJson' => Url::to(['/blogs/default/imperavi-get']),
////                    'imageUpload' => Url::to(['/blogs/default/imperavi-image-upload']),
////                    'fileUpload' => Url::to(['/blogs/default/imperavi-file-upload'])
//                    ]
//                ]
//            ),
            $form->field($model, 'content')->widget(CKEditor::className(), [
                'options' => ['rows' => 6],
                'clientOptions' => [
                    'filebrowserBrowseUrl' => Url::to(['/elfinder/manager']),
                    'filebrowserImageBrowseUrl' => Url::to(['/elfinder/manager', 'filter'=>'image'])
                ]
            ]),
//            $form->field($model, 'content')->widget(\mihaildev\ckeditor\CKEditor::className(),[
//                'editorOptions' => [
//                    //'preset' => 'full', //basic, standard, full
//                    //'inline' => false,
//                ],
//            ]),
            $form->field($model, 'status')->dropDownList($model::getStatusesList())
        ])
    ])
]) ?>

<?= Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'),
    ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

<?php Pjax::end() ?>

<?php
if ($model->isNewRecord) {
    $url = Url::to(['create']);
    $script = <<<JS
    var type = '[data-field="type"]';
    $(document).on('change', type, function() {
        $.pjax.reload({container: '#page-form', url: '{$url}', data: {type: $(this).val()}});
    });
JS;
    $this->registerJs($script);
}
?>


