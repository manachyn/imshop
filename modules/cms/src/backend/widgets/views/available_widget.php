<?php

/* @var $this yii\web\View */
/* @var $widget im\cms\models\widgets\Widget */
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $widget->getCMSTitle() ?></h3>
        <div class="box-tools pull-right">
            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <p><?= $widget->getCMSDescription() ?></p>
    </div>
</div>