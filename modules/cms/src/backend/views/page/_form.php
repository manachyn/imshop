<?php

use im\cms\Module;
use im\forms\components\FieldSet;
use im\forms\components\Tab;
use im\forms\components\TabSet;
use im\tree\widgets\JsTreeInput;
use im\wysiwyg\WysiwygEditor;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model im\cms\models\Page */
/* @var $form yii\widgets\ActiveForm */

$wysiwygConfig = Yii::$app->get('config')->get('wysiwyg.*');

?>

<?php Pjax::begin(['id' => 'page-form']) ?>

<?php $form = ActiveForm::begin(['id' => 'batch-update-form', 'options' => ['data-pjax' => 1]]); ?>

<?= new FieldSet('page', [
    new TabSet('tabs', [
        new Tab('main', Module::t('category', 'Main'), [
            $model->isNewRecord ? $form->field($model, 'type')->dropDownList($model::getTypesList(), ['data-field' => 'type']) : null,
            $form->field($model, 'parentId')->widget(JsTreeInput::className(), [
                'multiple' => false,
                'apiOptions' => [
                    'rootsUrl' => Url::to(['/api/v1/pages/roots']),
                    'childrenUrl' => Url::to(['/api/v1/pages/{id}/children']),
                    'parentsUrl' => Url::to(['/api/v1/pages/{id}/ancestors']),
                    'attributesMap' => ['id' => 'id', 'text' => 'title', 'children' => 'hasChildren', 'str' => 'string']
                ]
            ]),
            $form->field($model, 'title')->textInput(['maxlength' => true]),
            $form->field($model, 'slug')->textInput(['maxlength' => true]),
            $form->field($model, 'content')->widget(WysiwygEditor::className(), [
                'options' => ['rows' => 6],
                'editor' => $wysiwygConfig['wysiwyg.editor'],
                'preset' => $wysiwygConfig['wysiwyg.preset'],
                'fileManagerRoute' => ['/elfinder/manager']
            ]),
//            $form->field($model, 'content')->widget(CKEditor::className(), [
//                'options' => ['rows' => 6],
//                'clientOptions' => array_merge(ElFinder::getCKEditorOptions(['/elfinder/manager']), [
//                    'extraPlugins' => 'btgrid'
//                ])
//            ]),
//            $form->field($model, 'content')->widget(TinyMCE::className(), [
//                'options' => ['rows' => 6],
//                'preset' => 'full',
//                'clientOptions' => ElFinder::getTinyMCEOptions(['/elfinder/manager'])
//            ]),
            $form->field($model, 'status')->dropDownList($model::getStatusesList())
        ])
    ])
]) ?>

<?= Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'), [
    'class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success',
    'name' => 'submit-button'
]) ?>

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

