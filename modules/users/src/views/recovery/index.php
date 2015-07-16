<?php

use im\users\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model im\users\models\RecoveryForm */

$this->title = Module::t('registration', 'Password recovery');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="recovery-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($message = Yii::$app->getSession()->getFlash('recovery.success')) : ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php else: ?>

        <?php if ($message = Yii::$app->getSession()->getFlash('recovery.error')) : ?>
            <div class="alert alert-danger"><?= $message ?></div>
        <?php endif ?>

        <div class="row">
            <div class="col-md-6">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton(Module::t('registration', 'Submit'), ['class' => 'btn btn-primary', 'name' => 'recovery-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>

    <?php endif ?>

</div>