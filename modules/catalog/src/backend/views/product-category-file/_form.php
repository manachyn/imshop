<?php

use im\search\backend\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\catalog\models\ProductCategoryFile */
/* @var $form yii\widgets\ActiveForm|im\forms\components\DynamicActiveForm */
/* @var $fieldConfig array */

$fieldConfig = isset($fieldConfig) ? $fieldConfig : [];
?>

<?php if (!isset($form)) { $form = ActiveForm::begin(); } ?>

<?php if ($url = $model->getUrl(['w' => 225, 'h' => 126, 'fit' => 'crop'])) : ?>
    <img src="<?= $url ?>" class="img-responsive">
<?php endif ?>

<?= $form->field($model, 'filename', $fieldConfig)->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'title', $fieldConfig)->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'sort', $fieldConfig)->hiddenInput(['data-field' => 'sort'])->label(false) ?>

<?= $form->field($model, 'id', $fieldConfig)->hiddenInput()->label(false) ?>

<?php if (!isset($form)) {

    echo Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']);

    ActiveForm::end();
} ?>
