<?php

use app\modules\base\widgets\Block;
use im\cms\Module;
use app\modules\formBuilder\components\FieldSet;
use app\modules\formBuilder\components\Tab;
use app\modules\formBuilder\components\TabSet;
use yii\bootstrap\Tabs;
use yii\helpers\Html;
use vova07\imperavi\Widget as Imperavi;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model im\cms\models\Page */
/* @var $form yii\widgets\ActiveForm */

?>

<?php $form = ActiveForm::begin(['id' => 'batch-update-form', 'options' => ['data-pjax' => 1]]); ?>

<?= new FieldSet('category', [
    new TabSet('tabs', [
        new Tab('main', Module::t('category', 'Main'), [
            $form->field($model, 'title')->textInput(['maxlength' => true]),
            $form->field($model, 'slug')->textInput(['maxlength' => true]),
            $form->field($model, 'content')->widget(
                Imperavi::className(),
                [
                    'settings' => [
                        'minHeight' => 300,
//                    'imageGetJson' => Url::to(['/blogs/default/imperavi-get']),
//                    'imageUpload' => Url::to(['/blogs/default/imperavi-image-upload']),
//                    'fileUpload' => Url::to(['/blogs/default/imperavi-file-upload'])
                    ]
                ]
            ),
            $form->field($model, 'status')->dropDownList($model::getStatusesList())
        ])
    ])
]) ?>

<?= Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'),
    ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>


