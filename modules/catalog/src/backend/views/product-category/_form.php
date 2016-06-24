<?php

use dosamigos\fileinput\FileInput;
use dosamigos\fileupload\FileUpload;
use dosamigos\fileupload\FileUploadUI;
use im\base\widgets\ListView;
use im\catalog\Module;
use im\forms\components\ContentBlock;
use im\forms\components\FieldSet;
use im\forms\components\Tab;
use im\forms\components\TabSet;
use im\wysiwyg\WysiwygEditor;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\catalog\models\ProductCategory */
/* @var $form yii\widgets\ActiveForm */

//\yii\jui\JuiAsset::register($this);
//$dataProvider = new ActiveDataProvider([
//    'query' => $model->imageRelation()
//]);

$wysiwygConfig = Yii::$app->get('config')->get('wysiwyg.*');
?>

<?php $form = ActiveForm::begin(['id' => 'category-form', 'fieldClass' => 'im\forms\widgets\ActiveField', 'options' => ['enctype' => 'multipart/form-data']]); ?>

<?= new FieldSet('category', [
    new TabSet('tabs', [
        new Tab('main', Module::t('category', 'Main'), [
            $form->field($model, 'name')->textInput(['maxlength' => true]),
            $form->field($model, 'slug')->textInput(['maxlength' => true]),
            $form->field($model, 'description')->textarea(),
            $form->field($model, 'content')->widget(WysiwygEditor::className(), [
                'options' => ['rows' => 6],
                'editor' => $wysiwygConfig['wysiwyg.editor'],
                'preset' => $wysiwygConfig['wysiwyg.preset'],
                'fileManagerRoute' => ['/elfinder/manager']
            ]),
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
            $form->field($model, 'uploadedImage')->fileInput(['accept' => 'image/*']),
            new ContentBlock('image', $this->render('_image', [
                'model' => $model,
                'form' => $form
            ]))
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