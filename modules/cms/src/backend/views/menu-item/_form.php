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
    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'status')->dropDownList($model::getStatusesList()) ?>
<?php Block::end(); ?>

<?php $display = Block::begin(); ?>
<div class="row">
    <div class="col-sm-4">
        <?= $form->field($model, 'uploadedIcon')->fileInput(['accept' => 'image/*']) ?>
        <?= Html::hiddenInput('uploadedIcon') ?>
        <?php if ($icon = $model->icon) : ?>
        <?= $this->render('@im/cms/backend/views/menu-item-file/_form', ['model' => $icon, 'form' => $form]) ?>
        <?php endif ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'uploadedActiveIcon')->fileInput(['accept' => 'image/*']) ?>
        <?= Html::hiddenInput('uploadedActiveIcon') ?>
        <?php if ($activeIcon = $model->icon) : ?>
        <?= $this->render('@im/cms/backend/views/menu-item-file/_form', ['model' => $activeIcon, 'form' => $form]) ?>
        <?php endif ?>
    </div>
    <div class="col-sm-4">
        <?= $form->field($model, 'uploadedVideo')->fileInput(['accept' => 'video/*']) ?>
        <?= Html::hiddenInput('uploadedVideo') ?>
        <?php if ($video = $model->video) : ?>
        <?= $this->render('@im/cms/backend/views/menu-item-file/_form', ['model' => $video, 'form' => $form]) ?>
        <?php endif ?>
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