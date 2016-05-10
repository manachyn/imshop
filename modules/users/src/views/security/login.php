<?php

use im\users\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $module im\users\Module */
/* @var $model im\users\models\LoginForm */

$this->title = Module::t('login', 'Sign in');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="security-login">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($message = Yii::$app->getSession()->getFlash('reset.success')) : ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php elseif ($message = Yii::$app->getSession()->getFlash('reset.token.error')): ?>
        <div class="alert alert-danger"><?= $message ?></div>
    <?php else: ?>

        <?php if ($message = Yii::$app->getSession()->getFlash('reset.error')) : ?>
            <div class="alert alert-danger"><?= $message ?></div>
        <?php endif ?>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'options' => ['class' => 'form-horizontal'],
            'fieldConfig' => [
                'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                'labelOptions' => ['class' => 'col-lg-1 control-label'],
            ],
        ]); ?>

        <?= $form->field($model, 'username') ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'rememberMe', [
            'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ])->checkbox() ?>

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>

                <br/><br/>

                <p><?= Html::a(Module::t('login', 'Forgot password?'), ['/users/recovery']) ?></p>
                <?php if ($module->registrationConfirmation): ?>
                    <p><?= Html::a(Module::t('login', 'Didn\'t receive confirmation message?'), ['/users/registration/resend']) ?></p>
                <?php endif ?>
                <?php if ($module->enableRegistration): ?>
                    <p><?= Html::a(Module::t('login', 'Don\'t have an account? Sign up!'), ['/users/registration/register']) ?></p>
                <?php endif ?>

            </div>
        </div>

        <?php ActiveForm::end(); ?>

        <?php if (Yii::$app->get('authClientCollection', false)): ?>
            <div>
                <?= yii\authclient\widgets\AuthChoice::widget([
                    'baseAuthUrl' => ['/users/auth/auth'],
                    'popupMode' => false
                ]) ?>
            </div>
        <?php endif; ?>

    <?php endif ?>

</div>
