<?php

/* @var $this yii\web\View */
/* @var $widget im\cms\models\Widget */
/* @var $form yii\widgets\ActiveForm|im\forms\components\DynamicActiveForm */
/* @var $widgetArea im\cms\models\WidgetArea */
/* @var $index integer */

$fieldConfig = [];
$fieldConfig['tabularIndex'] = $index;
if (isset($widgetArea)) {
    $fieldConfig['namePrefix'] = "Widgets[$widgetArea->code]";
}
$editView = $widget->getEditView();
?>

<div class="box box-primary box-solid selected-widget<?php if(!$editView) { echo ' collapsed-box'; } ?>">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $widget->getCMSTitle() ?></h3>
        <div class="box-tools pull-right">
            <?php if ($editView) { ?>
            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <?php } ?>
            <button class="btn btn-box-tool" data-action="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <div class="box-body" data-cont="widget-form">
        <?php if ($editView) { ?>
            <?= $this->render($editView, ['model' => $widget, 'form' => $form, 'fieldConfig' => $fieldConfig]) ?>
        <?php } ?>
        <?= $form->field($widget, 'id', $fieldConfig)->hiddenInput()->label(false) ?>
        <?= $form->field($widget, 'type', $fieldConfig)->hiddenInput()->label(false) ?>
        <?= $form->field($widget, 'sort', $fieldConfig)->hiddenInput(['data-field' => 'sort', 'value' => $index])->label(false) ?>
    </div>
</div>