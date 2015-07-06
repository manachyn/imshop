<?php

use im\cms\models\WidgetArea;
use im\cms\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model im\cms\models\WidgetArea */
/* @var $widgetArea im\cms\backend\widgets\WidgetArea */
/* @var $form yii\widgets\ActiveForm|im\forms\components\DynamicActiveForm */

$model->id = $model->isNewRecord ? $model->code : $model->id;
?>

<h4><?= $model->title ?></h4>

<?php if ($model->display == WidgetArea::DISPLAY_ALWAYS) {
    echo $form->field($model, "[{$model->id}]display")->hiddenInput()->label(false);
} else {
    echo $form->field($model, "[{$model->id}]display")->dropDownList(WidgetArea::getDisplayOptions(), ['data-action' => 'display']);
} ?>

<?= $form->field($model, "[{$model->id}]code")->hiddenInput()->label(false) ?>

<?= Html::beginTag('div', $widgetArea->dropAreaOptions); ?>
<?php
if ($widgets = $model->widgets) {
    foreach ($widgets as $index => $widget) {
        echo $this->render('/widget-area/_selected_widget', ['widget' => $widget, 'form' => $form, 'widgetArea' => $model, 'index' => $index + 1]);
    }
} else { ?>
    <div class="drop-area-placeholder" data-cont="drop-area-placeholder"><?= Module::t('widget-area', 'Drag widgets here') ?></div>
<?php } ?>
<?= Html::endTag('div') ?>
<div data-cont="temp"></div>
