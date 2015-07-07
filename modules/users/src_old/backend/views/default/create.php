<?php

use app\modules\users\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\modules\users\models\User */
/* @var $profile app\modules\users\models\Profile */

$this->title = Module::t('user', 'Users');
$this->params['subtitle'] = Module::t('users', 'User creation');
$this->params['breadcrumbs'] = [['label' => $this->title, 'url' => ['index']], $this->params['subtitle']];
?>

<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->params['subtitle'] ?></h3>
        <div class="box-tools pull-right">
            <?= Html::a('<i class="fa fa-reply"></i>', ['index'], ['class' => 'btn btn-sm btn-default', 'title' => Module::t('module', 'Cancel')]) ?>
        </div>
    </div>
    <div class="box-body">
        <?= $this->render('_form', [
            'user' => $user,
            'profile' => $profile
        ]) ?>
    </div>
</div>
