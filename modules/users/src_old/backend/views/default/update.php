<?php

use app\modules\users\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\modules\users\models\User */
/* @var $profile app\modules\users\models\Profile */

$this->title = Module::t('users', 'Users');
$this->params['subtitle'] = Module::t('users', 'User updating');
$this->params['breadcrumbs'] = [['label' => $this->title, 'url' => ['index']], $this->params['subtitle']];
$boxButtons = ['{cancel}'];
if (Yii::$app->user->can('BackendCreateUsers')) {
    $boxButtons[] = '{create}';
}
if (Yii::$app->user->can('BackendDeleteUsers')) {
    $boxButtons[] = '{delete}';
}
$boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null;
?>

<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->params['subtitle'] ?></h3>
        <div class="box-tools pull-right">
            <?= Html::a('<i class="fa fa-reply"></i>', ['index'], ['class' => 'btn btn-sm btn-default', 'title' => Module::t('module', 'Cancel')]) ?>
            <?= Html::a('<i class="fa fa-plus"></i>', ['create'], ['class' => 'btn btn-sm btn-default', 'title' => Module::t('module', 'Create')]) ?>
            <?= Html::a('<i class="fa fa-trash-o"></i>', ['delete', 'id' => $model->id], ['class' => 'btn btn-sm btn-default', 'title' => Module::t('module', 'Delete'), 'data-confirm' => Module::t('module', 'Are you sure you want to delete this item?'), 'data-method' => 'delete']) ?>
        </div>
    </div>
    <div class="box-body">
        <?= $this->render('_form', [
            'user' => $user,
            'profile' => $profile
        ]) ?>
    </div>
</div>
