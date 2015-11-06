<?php

use im\base\widgets\ListViewAsset;
use im\catalog\models\CategoriesFacet;
use im\search\Module;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model CategoriesFacet */
/* @var $form yii\widgets\ActiveForm */

ListViewAsset::register($this);
?>

<?php Pjax::begin(['id' => 'facet-form-cont']) ?>

<div class="facet-form">

    <?php $form = ActiveForm::begin(['id' => 'facet-form', 'fieldClass' => 'im\forms\widgets\ActiveField']); ?>

    <?php if ($model->isNewRecord) :
        echo $form->field($model, 'type')->dropDownList($model::getTypesList(), ['data-field' => 'type']) ?>
    <?php endif ?>

    <?= $form->field($model, 'label')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true])->hint(Module::t('facet', 'Will be used in the search query')) ?>

    <?= $form->field($model, 'index_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'multivalue')->checkbox(['data-field' => 'multivalue'])->hint(Module::t('facet', 'Whether facet can has multiple selected values')) ?>

    <?= $form->field($model, 'operator', ['options' => ['data-field' => 'operator', 'style' => !$model->isMultivalue() ? 'display: none;' : '']])->dropDownList($model::getOperatorsList()) ?>

    <?= Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>

</div>

<?php Pjax::end() ?>

<?php
$url = Url::to(['create']);
$formId = $form->getId();
$script = <<<JS
    var facetType = '[data-field="type"]';
    var multivalue = '[data-field="multivalue"]';
    var operator = '[data-field="operator"]';
    $(document).on('change', multivalue, function() {
        if ($(this).is(':checked')) {
            $('#{$formId}').find(operator).show();
        } else {
            $('#{$formId}').find(operator).hide();
        }
    });
    $(document).on('change', facetType, function() {
        $.pjax.reload({container: '#facet-form-cont', url: '{$url}', data: {type: $(this).val()}});
    });
JS;
$this->registerJs($script);
?>
