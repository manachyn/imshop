<?php

use im\base\Module;

/* @var $this yii\web\View */
/* @var $itemContent string */
/* @var $widget im\base\widgets\ListView */

?>
<?php if ($widget->addLabel !== false) : ?>
    <button type="button" class="btn btn-success" data-action="add">
        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> <?= $widget->addLabel ? $widget->addLabel : Module::t('list-view', 'Add') ?>
    </button>
    <br /><br />
<?php endif ?>
