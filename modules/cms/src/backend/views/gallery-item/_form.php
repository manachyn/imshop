<?php

use im\cms\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\cms\models\MenuItemFile */
/* @var $form yii\widgets\ActiveForm|im\forms\components\DynamicActiveForm */
/* @var $fieldConfig array */

$fieldConfig = isset($fieldConfig) ? $fieldConfig : [];
?>

<?php if (!isset($form)) { $form = ActiveForm::begin(); } ?>

<?php if ($url = $model->getUrl()) : ?>
    <?php if (strpos($model->mime_type, 'image') === 0) : ?>
        <?php if ($url = $model->getUrl(['w' => 225, 'h' => 126, 'fit' => 'crop'])) : ?>
            <img src="<?= $url ?>" class="img-responsive">
        <?php endif ?>
    <?php elseif (strpos($model->mime_type, 'video') === 0) : ?>
        <video width="100%" controls>
            <source src="<?= $url ?>" type="<?= $model->mime_type ?>">
        </video>
    <?php endif ?>
<?php endif ?>


<?= $form->field($model, 'filename', $fieldConfig)->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'title', $fieldConfig)->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'caption', $fieldConfig)->textarea(['maxlength' => true]) ?>

<?= $form->field($model, 'alt_text', $fieldConfig)->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'status', $fieldConfig)->checkbox() ?>

<?= $form->field($model, 'sort', $fieldConfig)->hiddenInput(['data-field' => 'sort'])->label(false) ?>

<?= $form->field($model, 'id', $fieldConfig)->hiddenInput()->label(false) ?>

<?php if (!isset($form)) {

    echo Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']);

    ActiveForm::end();
} ?>
