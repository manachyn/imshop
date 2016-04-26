<?php

use im\base\Module;

/* @var $this yii\web\View */
/* @var $widget im\base\widgets\RelationWidget */

$models = $widget->relation->all();
?>
<?php if ($widget->addLabel !== false) : ?>
<button type="button" class="btn btn-success" data-action="add">
    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> <?= $widget->addLabel ? $widget->addLabel : Module::t('relation-widget', 'Add') ?>
</button>
<br /><br />
<?php endif ?>
<div class="list" data-cont="list">
<?php foreach ($models as $index => $model) : ?>
    <?= $this->render('relation_widget_item', [
        'model' => $model,
        'sortable' => $widget->sortable,
        'index' => $index + 1,
        'modelView' => $widget->modelView,
        'form' => $widget->form
    ]) ?>
<?php endforeach ?>
</div>
<div data-cont="temp"></div>
