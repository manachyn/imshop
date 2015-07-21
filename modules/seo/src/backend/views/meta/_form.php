<?php

use im\seo\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model im\seo\models\Meta */

?>

<?php if (!isset($form)) $form = ActiveForm::begin(); ?>

<?= $this->render('@im/seo/backend/views/meta/_main', [
    'model' => $model,
    'form' => $form
]) ?>

<?= $this->render('@im/seo/backend/views/meta/_social', [
    'model' => $model,
    'form' => $form
]) ?>

<?php if (!isset($form)) { ?>
<div class="form-group">
    <?= Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']) ?>
</div>
<?php ActiveForm::end(); } ?>

