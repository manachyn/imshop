<?php

use im\users\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model im\users\models\ResendForm */

$this->title = Module::t('registration', 'Request new confirmation message');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="registration-resend">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php if ($message = Yii::$app->getSession()->getFlash('resend.success')) : ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php else: ?>

        <div class="row">
            <div class="col-md-6">
                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton(Module::t('registration', 'Submit'), ['class' => 'btn btn-primary', 'name' => 'resend-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>

    <?php endif ?>

</div>
