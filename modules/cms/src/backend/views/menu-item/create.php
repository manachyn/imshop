<?php

use im\cms\Module;

/* @var $this yii\web\View */
/* @var $model im\cms\models\MenuItem */

$this->title = Module::t('menu-item', 'Menu items');
$this->params['subtitle'] = Module::t('menu-item', 'Menu item creation');
$this->params['breadcrumbs'] = [['label' => $this->title, 'url' => ['index']], $this->params['subtitle']];
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->params['subtitle'] ?></h3>
    </div>
    <div class="box-body">
        <?= $this->render('_form', [
            'model' => $model
        ]) ?>
    </div>
</div>