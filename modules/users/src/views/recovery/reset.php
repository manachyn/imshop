<?php

use im\users\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model im\users\models\PasswordResetForm */

$this->title = Module::t('registration', 'Reset your password');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="recovery-reset">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($message = Yii::$app->getSession()->getFlash('reset.success')) : ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php elseif ($message = Yii::$app->getSession()->getFlash('reset.token.error')): ?>
        <div class="alert alert-danger"><?= $message ?></div>
    <?php else: ?>

        <?php if ($message = Yii::$app->getSession()->getFlash('reset.error')) : ?>
            <div class="alert alert-danger"><?= $message ?></div>
        <?php endif ?>

        <div class="row">
            <div class="col-md-6">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'password2')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton(Module::t('recovery', 'Reset'), ['class' => 'btn btn-primary', 'name' => 'reset-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>

    <?php endif ?>

</div>
