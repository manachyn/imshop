<?php

/* @var $this yii\web\View */
use yii\helpers\Html;

/* @var $widget im\base\widgets\RelationWidget */

$models = $widget->relation->all();
?>

<button type="button" class="btn btn-success" data-action="add">
    <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add
</button>
<?php foreach ($models as $index => $model) : ?>
    <div class="list-item media">
        <?php if ($widget->sortable) : ?>
        <div class="list-item-left list-item-sort media-left">
            <i class="fa fa-sort"></i>
        </div>
        <?php endif ?>
        <div class="list-item-body media-body">
            <?= $this->render($widget->modelView, ['model' => $model, 'form' => $widget->form, 'fieldConfig' => ['tabularIndex' => $index + 1]]) ?>
        </div>
    </div>
<?php endforeach ?>
<div data-cont="temp"></div>
