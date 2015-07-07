<?php
use im\users\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user yii\web\User */

$this->title = Module::t('module', 'Your account has been created.');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="registration-success">
    <h1><?= Html::encode($this->title) ?></h1>
</div>
