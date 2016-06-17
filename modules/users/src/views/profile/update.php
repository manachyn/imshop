<?php

use im\users\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $module im\users\Module */
/* @var $model im\users\models\ProfileForm */

$this->title = Module::t('registration', 'Profile');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registration-success">

    <?php if ($message = Yii::$app->getSession()->getFlash('profile')) : ?>
        <div class="alert alert-danger"><?= $message ?></div>
    <?php endif ?>

    <div class="row">
        <div class="col-md-6">
            <?php $form = ActiveForm::begin([
                'id' => 'profile-form',
                'enableClientValidation' => false,
                'enableAjaxValidation' => true
            ]); ?>

            <?= $form->field($model, 'username') ?>

            <?= $form->field($model, 'email') ?>

            <?= $form->field($model, 'firstName') ?>

            <?= $form->field($model, 'lastName') ?>

            <h2><?= Module::t('profile', 'Change password') ?></h2>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'password2')->passwordInput() ?>

            <?= $form->field($model, 'currentPassword')->passwordInput() ?>

            <div class="form-group">
                <?= Html::submitButton(Module::t('profile', 'Update'), ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <?= Html::a(Module::t('registration', 'Already registered? Sign in!'), ['/user/security/login']) ?>

</div>
