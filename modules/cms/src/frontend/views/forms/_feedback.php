<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model im\cms\models\FeedbackRequest */
/* @var $form im\cms\frontend\forms\FeedbackForm */

$formWidget = ActiveForm::begin(['id' => 'feedback-form']); ?>

<?= $formWidget->field($model, 'name') ?>
<?= $formWidget->field($model, 'email') ?>
<?= $formWidget->field($model, 'phone') ?>
<?= $formWidget->field($model, 'text')->textarea() ?>

<div class="form-group">
    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>
