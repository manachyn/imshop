<?php

/**
 * Permission form view.
 *
 * @var \yii\base\View $this View
 * @var \yii\widgets\ActiveForm $form Form
 * @var \yii\base\DynamicModel $model Model
 * @var \vova07\themes\admin\widgets\Box $box Box widget instance
 * @var array $permissionArray Permissions array
 * @var array $ruleArray Rules array
 */

use app\modules\rbac\Module;
use vova07\select2\Widget;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>
<?php $form = ActiveForm::begin(); ?>
<?php $box->beginBody(); ?>
    <div class="row">
        <div class="col-sm-6">
            <?= $form->field($model, 'name')->label(Module::t('rbac', 'BACKEND_PERMISSIONS_ATTR_NAME')) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'ruleName')->widget(Widget::className(), [
                'options' => [
                    'prompt' => Module::t('rbac', 'BACKEND_PERMISSIONS_RULE_NAME_PROMPT'),
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
            <?= $form->field($model, 'description')->textarea()->label(Module::t('rbac', 'BACKEND_PERMISSIONS_ATTR_DESCRIPTION')) ?>
        </div>
        <div class="col-sm-6">
            <?= $form->field($model, 'data')->textarea()->label(Module::t('rbac', 'BACKEND_PERMISSIONS_ATTR_DATA')) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <?= $form->field($model, 'children')->widget(Widget::className(), [
                'options' => [
                    'multiple' => true,
                    'placeholder' => Module::t('rbac', 'BACKEND_PERMISSIONS_CHILDREN_PROMPT')
                ],
                'settings' => [
                    'width' => '100%',
                ],
                'items' => $permissionArray
            ])->label(Module::t('rbac', 'BACKEND_PERMISSIONS_ATTR_CHILDREN')) ?>
        </div>
    </div>
<?php $box->endBody(); ?>
<?php $box->beginFooter(); ?>
<?= Html::submitButton(!isset($update) ? Module::t('rbac', 'BACKEND_PERMISSIONS_CREATE_SUBMIT') : Module::t('rbac', 'BACKEND_PERMISSIONS_UPDATE_SUBMIT'), [
    'class' => !isset($update) ? 'btn btn-primary btn-large' : 'btn btn-success btn-large'
]) ?>
<?php $box->endFooter(); ?>
<?php ActiveForm::end(); ?>