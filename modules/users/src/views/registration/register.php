<?php

use im\users\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $module im\users\Module */
/* @var $model im\users\models\RegistrationForm */

$this->title = Module::t('registration', 'Sign up');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration-success">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($message = Yii::$app->getSession()->getFlash('registration.success')) : ?>

        <div class="alert alert-success"><?= $message ?></div>

        <?php if ($info = Yii::$app->getSession()->getFlash('registration.info')) : ?>
            <div class="alert alert-info"><?= $info ?></div>
        <?php endif ?>

    <?php else: ?>

        <?php if ($message = Yii::$app->getSession()->getFlash('registration.error')) : ?>
            <div class="alert alert-danger"><?= $message ?></div>
        <?php endif ?>

        <div class="row">
            <div class="col-md-6">
                <?php $form = ActiveForm::begin(['id' => 'registration-form', 'enableClientValidation' => false]); ?>

                <?= $form->field($model, 'username') ?>

                <?= $form->field($model, 'email') ?>

                <?php if (!$module->passwordAutoGenerating): ?>

                    <?= $form->field($model, 'password')->passwordInput() ?>

                    <?= $form->field($model, 'password2')->passwordInput() ?>

                <?php endif ?>

                <?= $form->field($model, 'firstName') ?>

                <?= $form->field($model, 'lastName') ?>

                <div class="form-group">
                    <?= Html::submitButton(Module::t('registration', 'Sign up'), ['class' => 'btn btn-primary', 'name' => 'register-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>

        <?= Html::a(Module::t('registration', 'Already registered? Sign in!'), ['/user/security/login']) ?>

    <?php endif ?>

</div>
