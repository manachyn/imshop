<?php

use im\rbac\Module;

/* @var $this yii\web\View */
/* @var $model im\rbac\models\Assignment */

$this->title = Module::t('permission', 'Roles');
$this->params['subtitle'] = Module::t('permission', 'Roles assignment');
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