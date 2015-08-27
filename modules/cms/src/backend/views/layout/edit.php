<?php

use im\cms\backend\widgets\AvailableWidget;
use im\cms\backend\widgets\WidgetArea;
use im\cms\Module;

/* @var $this yii\web\View */
/* @var $layout im\cms\components\layout\Layout */
/* @var $form yii\widgets\ActiveForm|im\forms\components\DynamicActiveForm */
/* @var $layoutManager im\cms\components\layout\LayoutManager */

$layoutManager = Yii::$app->get('layoutManager');
if (is_string($layout)) {
    $layout = $layoutManager->getLayout($layout);
}
$availableWidgets = $layoutManager->getAvailableWidgets();
?>

<div class="row">
    <div class="col-sm-6">
        <h4><?= Module::t('layout', 'Widgets') ?></h4>
        <?php foreach ($layout->widgetAreas as $widgetArea) {
            echo WidgetArea::widget(['model' => $widgetArea, 'form' => $form]);
        } ?>
    </div>
    <div class="col-sm-6">
        <h4><?= Module::t('layout', 'Available widgets') ?></h4>
        <?php foreach ($availableWidgets as $widget) {
            echo AvailableWidget::widget(['widget' => $widget]);
        } ?>
    </div>
</div>



