<?php
use im\users\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = Module::t('registration', 'Sign up');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="registration-success">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (Yii::$app->getSession()->has('registration.success')) : ?>
        <div class="alert alert-success"><?= Yii::$app->getSession()->getFlash('registration.success', null, true) ?></div>
    <?php endif ?>
    <?php if (Yii::$app->getSession()->has('registration.info')) : ?>
        <div class="alert alert-info"><?= implode('<br>', Yii::$app->getSession()->getFlash('registration.info', null, true)) ?></div>
    <?php endif ?>
</div>
