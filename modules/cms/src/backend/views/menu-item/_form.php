<?php

use im\base\widgets\Block;
use im\base\widgets\ListView;
use im\cms\Module;
use yii\bootstrap\Tabs;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\cms\models\MenuItem */
/* @var $form yii\widgets\ActiveForm */

?>

<?php $form = ActiveForm::begin([
    'id' => 'menu-item-form',
    'fieldClass' => 'im\forms\widgets\ActiveField',
    'options' => ['enctype' => 'multipart/form-data']
]); ?>

<?php $main = Block::begin(); ?>
    <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'target_blank')->checkbox() ?>
    <?= $form->field($model, 'css_classes')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'rel')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'status')->dropDownList($model::getStatusesList()) ?>
    <?= $form->field($model, 'visibility')->textInput(['maxlength' => true]) ?>
<?php Block::end(); ?>

<?php $display = Block::begin(); ?>
<?= $form->field($model, 'items_display')->dropDownList($model::getDisplayList()) ?>
<?= $form->field($model, 'items_css_classes')->textInput(['maxlength' => true]) ?>
<div class="row">
    <div class="col-sm-4">
        <?= $form->field($model, 'uploadedIcon')->fileInput(['accept' => 'image/*']) ?>
        <?= Html::hiddenInput('uploadedIcon') ?>
        <?= ListView::widget([
            'dataProvider' => new ActiveDataProvider([
                'query' => $model->iconRelation()
            ]),
            'itemView' => '@im/cms/backend/views/menu-item-file/_form',
            'addLabel' => false,
            'viewParams' => ['form' => $form, 'fieldConfig' => ['namePrefix' => 'uploadedIcon']]
        ]); ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'uploadedActiveIcon')->fileInput(['accept' => 'image/*']) ?>
        <?= Html::hiddenInput('uploadedActiveIcon') ?>
        <?= ListView::widget([
            'dataProvider' => new ActiveDataProvider([
                'query' => $model->activeIconRelation()
            ]),
            'itemView' => '@im/cms/backend/views/menu-item-file/_form',
            'addLabel' => false,
            'viewParams' => ['form' => $form, 'fieldConfig' => ['namePrefix' => 'uploadedActiveIcon']]
        ]); ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'uploadedVideo')->fileInput(['accept' => 'video/*']) ?>
        <?= Html::hiddenInput('uploadedVideo') ?>
        <?= ListView::widget([
            'dataProvider' => new ActiveDataProvider([
                'query' => $model->videoRelation()
            ]),
            'itemView' => '@im/cms/backend/views/menu-item-file/_form',
            'addLabel' => false,
            'viewParams' => ['form' => $form, 'fieldConfig' => ['namePrefix' => 'uploadedVideo']]
        ]); ?>
    </div>
</div>
<?php Block::end();

$tabs = [
    [
        'label' => Module::t('menu-item', 'Main'),
        'content' => $main,
        'active' => true
    ],
    [
        'label' => Module::t('menu-item', 'Display'),
        'content' => $display
    ]
];
?>

<div class="nav-tabs-custom">
    <?= Tabs::widget(['items' => $tabs]); ?>
</div>

<?= Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>