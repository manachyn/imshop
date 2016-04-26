<?php

use yii\db\ActiveRecord;

/* @var $this yii\web\View */
/* @var $sortable bool */
/* @var $model ActiveRecord */
/* @var $index integer */
/* @var $modelView string */
/* @var $form \yii\widgets\ActiveForm|\im\forms\components\DynamicActiveForm */

$fieldConfig = [];
if ($index) {
    $fieldConfig['tabularIndex'] = $index;
}
?>
<div class="list-item media">
    <?php if ($sortable) : ?>
    <div class="list-item-left list-item-sort media-left">
        <i class="fa fa-sort"></i>
    </div>
    <?php endif ?>
    <div class="list-item-body media-body">
        <?= $this->render($modelView, ['model' => $model, 'form' => $form, 'fieldConfig' => $fieldConfig]) ?>
    </div>
    <div class="list-item-right media-right">
        <button type="button" class="btn btn-danger" data-action="remove"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
    </div>
</div>
