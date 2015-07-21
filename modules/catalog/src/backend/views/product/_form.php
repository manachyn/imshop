<?php

use im\catalog\models\Brand;
use im\catalog\Module;
use im\forms\components\ContentBlock;
use im\forms\components\FieldSet;
use im\forms\components\Tab;
use im\forms\components\TabSet;
use im\tree\widgets\JsTreeInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\catalog\models\Product */
/* @var $form yii\widgets\ActiveForm */

?>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= new FieldSet('product', [
    new TabSet('tabs', [
        new Tab('main', Module::t('product', 'Main'), [
            $form->field($model, 'sku')->textInput(['maxlength' => true]),
            $form->field($model, 'title')->textInput(['maxlength' => true]),
            $form->field($model, 'slug')->textInput(['maxlength' => true]),
            $form->field($model, 'description')->textarea(['maxlength' => true]),
            $form->field($model, 'brand_id')->dropDownList(
                ArrayHelper::map(Brand::find()->asArray()->orderBy('name')->all(), 'id', 'name'),
                ['prompt' => '']
            ),
            $form->field($model, 'price')->textInput(),
            $form->field($model, 'status')->dropDownList($model::getStatusesList())
        ]),
        new Tab('attributes', Module::t('product', 'Attributes'), [
            new ContentBlock('attributes', $this->render('_attributes', [
                'model' => $model,
                'form' => $form
            ]))
        ], $form, $model),
        new Tab('categories', Module::t('product', 'Categories'), [
            $form->field($model, 'categories')->widget(JsTreeInput::className(), [
                'apiOptions' => [
                    'rootsUrl' => Url::to(['/api/v1/product-categories/roots']),
                    'childrenUrl' => Url::to(['/api/v1/product-categories/{id}/children']),
                    'parentsUrl' => Url::to(['/api/v1/product-categories/{id}/ancestors']),
                    'attributesMap' => ['id' => 'id', 'text' => 'name', 'children' => 'hasChildren', 'str' => 'string']
                ]
            ])
        ]),
        new Tab('images', Module::t('product', 'Images'), [
            $form->field($model, 'images[]', ['enableClientValidation' => false])->fileInput(['multiple' => true, 'accept' => 'image/*'])
        ])
    ])
]) ?>

<?= Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'),
    ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>

