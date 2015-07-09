<?php
use im\users\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = Module::t('module', 'Sign up');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="registration-success">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php if (Yii::$app->getSession()->has('registration.success')) : ?>
        <div class="alert alert-success"><?= Yii::$app->getSession()->getFlash('registration.success') ?></div>
    <?php endif ?>
</div>
