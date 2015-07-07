<?php

/**
 * Sign In page view.
 *
 * @var \yii\base\View $this View
 * @var \yii\widgets\ActiveForm $form Form
 * @var \app\modules\users\models\LoginForm $model Model
 */

use app\modules\users\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = Module::t('users', 'Sign In');
?>

<div class="login-box">
    <div class="login-logo">
        <a href="../../index2.html"><b>IM</b>Shop</a>
    </div><!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Sign in to start your session</p>
        <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'username')->textInput(['placeholder' => $model->getAttributeLabel('username')])->label(false) ?>
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => $model->getAttributeLabel('password')])->label(false) ?>
            <?= $form->field($model, 'rememberMe')->checkbox() ?>
            <div class="form-group">
                <?= Html::submitButton(Module::t('users', 'Sign In'), ['class' => 'btn btn-primary btn-flat']) ?>
            </div>
            <p><?= Html::a(Module::t('users', 'Recover password'), ['recovery']) ?></p>
        <?php ActiveForm::end(); ?>
    </div><!-- /.login-box-body -->
</div><!-- /.login-box -->