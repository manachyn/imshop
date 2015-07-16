<?php

use im\users\Module;
use yii\helpers\Html;

/* @var $this yii\web\View */

$this->title = Module::t('registration', 'Sign up confirmation');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration-confirm">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->getSession()->has('confirmation.success')) : ?>

        <div class="alert alert-success"><?= Yii::$app->getSession()->getFlash('confirmation.success') ?></div>

        <?php if (Yii::$app->user->getIsGuest()): ?>

            <p><?= Html::a(Module::t('registration', 'Go to my profile'), ['/users/profile']) ?></p>

            <p><?= Html::a(Module::t('registration', 'Go home'), Yii::$app->getHomeUrl()) ?></p>

        <?php else: ?>

            <p><?= Html::a(Module::t('registration', 'Login here'), ['/users/login']) ?></p>

        <?php endif; ?>

    <?php elseif (Yii::$app->getSession()->has('confirmation.error')): ?>

        <div class="alert alert-danger"><?= Yii::$app->getSession()->getFlash('confirmation.error') ?></div>

        <p><?= Html::a(Module::t('registration', 'Resend confirmation link'), ['/users/registration/resend']) ?></p>

    <?php endif ?>

</div>
