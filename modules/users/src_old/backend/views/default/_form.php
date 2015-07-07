<?php

use app\modules\users\models\User;
use app\modules\users\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $user app\modules\users\models\User */
/* @var $profile app\modules\users\models\Profile */
/* @var $form yii\widgets\ActiveForm */
/* @var $box app\themes\admin\widgets\Box */
?>

<!--<div class="user-form">-->
<!---->
<!--    --><?php //$form = ActiveForm::begin(); ?>
<!---->
<!--    --><?//= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>
<!---->
<!--    --><?//= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>
<!---->
<!--    --><?//= $form->field($model, 'role')->textInput(['maxlength' => 255]) ?>
<!---->
<!--    --><?//= $form->field($model, 'status')->textInput() ?>
<!---->
<!--    <div class="form-group">-->
<!--        --><?//= Html::submitButton($model->isNewRecord ? Yii::t('modules/users', 'Create') : Yii::t('modules/users', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
<!--    </div>-->
<!---->
<!--    --><?php //ActiveForm::end(); ?>
<!---->
<!--</div>-->

<?php $form = ActiveForm::begin(); ?>

<div class="row">
    <div class="col-sm-6">
        <?= $form->field($profile, 'first_name') ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($profile, 'last_name') ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <?= $form->field($user, 'username') ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($user, 'email') ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <?= $form->field($user, 'password')->passwordInput() ?>
    </div>
    <div class="col-sm-6">
        <?= $form->field($user, 'password2')->passwordInput() ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-6">
        <?=
        $form->field($user, 'status')->dropDownList(
            User::getStatusesList(),
            ['prompt' => Module::t('users', 'Select status')]
        ) ?>
    </div>
    <div class="col-sm-6">
        <?=
        $form->field($user, 'role')->dropDownList(
            User::getRolesList(),
            ['prompt' => Module::t('users', 'Select role')]
        ) ?>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
<!--        --><?//= $form->field($profile, 'avatar_url')->widget(Widget::className(),
//            [
//                'settings' => [
//                    'url' => ['fileapi-upload']
//                ],
//                'crop' => true,
//                'cropResizeWidth' => 100,
//                'cropResizeHeight' => 100
//            ]
//        ) ?>
    </div>
</div>

<?= Html::submitButton(
    $user->isNewRecord ? Module::t('users', 'Create') : Module::t('users', 'Update'),
    ['class' => $user->isNewRecord ? 'btn btn-primary btn-large' : 'btn btn-success btn-large']
) ?>

<?php ActiveForm::end(); ?>
