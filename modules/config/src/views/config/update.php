<?php

use im\config\Module;

/* @var $this yii\web\View */
/* @var $model im\config\components\EditableConfigInterface */

$this->title = Module::t('config', 'Config');
$this->params['subtitle'] = $model->getTitle();
$this->params['breadcrumbs'] = [$this->title, $this->params['subtitle']];
?>
<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->params['subtitle'] ?></h3>
    </div>
    <div class="box-body">
        <?= $this->render($model->getEditView(), [
            'model' => $model,
        ]) ?>
    </div>
</div>
