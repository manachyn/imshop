<?php

use dosamigos\fileinput\FileInput;
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

<?php $form = ActiveForm::begin(['id' => 'category-form', 'enableClientValidation' => false, 'options' => ['enctype' => 'multipart/form-data']]); ?>

<?php foreach($model->images as $index => $item): ?>
    <?= $form->field($item, "[$item->id]title")->textInput(['name' => "images[$item->id][title]"]); ?>
    <?= $form->field($item, "[$item->id]sort")->hiddenInput(['name' => "images[$item->id][sort]", 'value' => $index + 1])->label(false); ?>
<?php endforeach; ?>

<?= new FieldSet('category', [
    new TabSet('tabs', [
        new Tab('main', Module::t('category', 'Main'), [
            $form->field($model, 'name')->textInput(['maxlength' => true]),
            $form->field($model, 'slug')->textInput(['maxlength' => true]),
            $form->field($model, 'description')->textarea(),
            $form->field($model, 'status')->dropDownList($model::getStatusesList()),
        ]),
        new Tab('images', Module::t('category', 'Images'), [
//            new ContentBlock('image-uploader', FileInput::widget([
//                'model' => $model,
//                'attribute' => 'image', // image is the attribute
//                // using STYLE_IMAGE allows me to display an image. Cool to display previously
//                // uploaded images
////                'thumbnail' => $model->getAvatarImage(),
//                'style' => FileInput::STYLE_IMAGE
//            ])),
//            new ContentBlock('image-uploader', FileUploadUI::widget([
//                'model' => $model,
//                'attribute' => 'image',
//                'url' => ['/filesystem/uploads/upload', 'id' => $model->id],
//                'fieldOptions' => [
//                    'accept' => 'image/*'
//                ],
//                'clientOptions' => [
//                    'maxFileSize' => 2000000
//                ],
//            ])),
            $form->field($model, 'uploadedImage', ['enableClientValidation' => false])->fileInput(['accept' => 'image/*']),
            $form->field($model, 'uploadedImages[]', ['enableClientValidation' => false])->fileInput(['multiple' => true, 'accept' => 'image/*'])
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