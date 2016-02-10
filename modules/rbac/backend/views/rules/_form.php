<?php

/**
 * Rule form view.
 *
 * @var \yii\base\View $this View
 * @var \yii\widgets\ActiveForm $form Form
 * @var \yii\base\Model $model Model
 * @var \vova07\themes\admin\widgets\Box $box Box widget instance
 */

use vova07\blogs\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin(); ?>
<?php $box->beginBody(); ?>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'namespace') ?>
        </div>
    </div>
<?php $box->endBody(); ?>
<?php $box->beginFooter(); ?>
<?= Html::submitButton(Module::t('rbac', 'BACKEND_RULES_CREATE_SUBMIT'), [
    'class' => 'btn btn-primary btn-large'
]) ?>
<?php $box->endFooter(); ?>
<?php ActiveForm::end(); ?>