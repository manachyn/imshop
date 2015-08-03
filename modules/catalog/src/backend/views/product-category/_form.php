<?php

use dosamigos\fileupload\FileUpload;
use dosamigos\fileupload\FileUploadUI;
use im\catalog\Module;
use im\forms\components\ContentBlock;
use im\forms\components\FieldSet;
use im\forms\components\Tab;
use im\forms\components\TabSet;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\catalog\models\ProductCategory */
/* @var $form yii\widgets\ActiveForm */

\yii\jui\JuiAsset::register($this);

?>

<?php $form = ActiveForm::begin(['id' => 'category-form', 'options' => ['enctype' => 'multipart/form-data']]); ?>

<?= new FieldSet('category', [
    new TabSet('tabs', [
        new Tab('main', Module::t('category', 'Main'), [
            $form->field($model, 'name')->textInput(['maxlength' => true]),
            $form->field($model, 'slug')->textInput(['maxlength' => true]),
            $form->field($model, 'description')->textarea(),
            $form->field($model, 'status')->dropDownList($model::getStatusesList()),
        ]),
        new Tab('images', Module::t('category', 'Images'), [
            new ContentBlock('image-uploader', FileUploadUI::widget([
                'model' => $model,
                'attribute' => 'image',
                'url' => ['/filesystem/uploads/upload', 'id' => $model->id],
                'fieldOptions' => [
                    'accept' => 'image/*'
                ],
                'clientOptions' => [
                    'maxFileSize' => 2000000
                ],
            ])),
//            $form->field($model, 'image')->widget(
//                '\trntv\filekit\widget\Upload',
//                [
//                    'url' => ['/filesystem/uploads/upload', 'id' => $model->id],
//                    'sortable'=>true,
//                    'fileuploadOptions'=>[
//                        'maxFileSize'=>10000000, // 10 MiB
//                        'maxNumberOfFiles'=>3
//                    ]
//                ]
//            )
//            new ContentBlock('image-uploader', FileUpload::widget([
//                'model' => $model,
//                'attribute' => 'image',
//                'url' => ['/filesystem/uploads/upload', 'id' => $model->id],
//                'options' => ['accept' => 'image/*'],
//                'clientOptions' => [
//                    'maxFileSize' => 2000000
//                ]
//            ]))
        ])
    ])
]) ?>

<?= Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'),
    ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>