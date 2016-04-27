<?php

use im\elfinder\ElFinderAsset;
use im\elfinder\widgets\ElFinder;
use im\filesystem\Module;

/* @var $this yii\web\View */

ElFinderAsset::noConflict($this);
$this->title = Module::t('filesystem', 'Filesystem');
$this->params['subtitle'] = Module::t('filesystem', 'File manager');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->params['subtitle'] ?></h3>
    </div>

    <div class="box-body">
       <?= ElFinder::widget() ?>
    </div>
</div>
