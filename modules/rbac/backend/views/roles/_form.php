<?php

/**
 * Role form view.
 *
 * @var \yii\base\View $this View
 * @var \yii\widgets\ActiveForm $form Form
 * @var \yii\base\DynamicModel $model Model
 * @var \app\themes\admin\widgets\Box $box Box widget instance
 * @var array $roleArray Roles array
 * @var array $ruleArray Rules array
 * @var array $permissionArray Permissions array
 */

use vova07\blogs\Module;
use vova07\select2\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin(); ?>
<?php $box->beginBody(); ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'name') ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'ruleName')->widget(Widget::className(), [
                'options' => [
                    'prompt' => Module::t('rbac', 'BACKEND_ROLES_RULE_NAME_PROMPT'),
                ],
                'settings' => [
                    'width' => '100%',
                ],
                'items' => $ruleArray
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'description')->textarea() ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'data')->textarea() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'rolesChildren')->widget(Widget::className(), [
                'options' => [
                    'multiple' => true,
                    'placeholder' => Module::t('rbac', 'BACKEND_ROLES_ROLES_PROMPT')
                ],
                'settings' => [
                    'width' => '100%',
                ],
                'items' => $roleArray
            ]) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'permissionsChildren')->widget(Widget::className(), [
                'options' => [
                    'multiple' => true,
                    'placeholder' => Module::t('rbac', 'BACKEND_ROLES_PERMISSIONS_PROMPT')
                ],
                'settings' => [
                    'width' => '100%',
                ],
                'items' => $permissionArray
            ]) ?>
        </div>
    </div>
<?php $box->endBody(); ?>
<?php $box->beginFooter(); ?>
<?= Html::submitButton(!isset($update) ? Module::t('rbac', 'BACKEND_ROLES_CREATE_SUBMIT') : Module::t('rbac', 'BACKEND_ROLES_UPDATE_SUBMIT'), [
    'class' => !isset($update) ? 'btn btn-primary btn-large' : 'btn btn-success btn-large'
]) ?>
<?php $box->endFooter(); ?>
<?php ActiveForm::end(); ?>