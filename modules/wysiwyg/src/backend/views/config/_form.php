<?php

use im\wysiwyg\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\wysiwyg\Config */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="config-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'editor')->dropDownList($model->getEditorsList()) ?>

    <?= $form->field($model, 'preset')->dropDownList($model->getPresetsList()) ?>

    <div class="form-group">
        <?= Html::submitButton(Module::t('module', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


