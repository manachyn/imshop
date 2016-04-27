<?php

use im\base\widgets\ListView;
use im\base\widgets\ListViewAsset;
use im\search\components\query\facet\EditableFacetValueInterface;
use im\search\models\Facet;
use im\search\models\FacetValue;
use im\search\Module;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model Facet */
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

    <label><?= Module::t('facet', 'Attribute values as facet values') ?></label>
    <?= $form->field($model, 'entity_type')->dropDownList(
        $model::getEntityTypesList(),
        ['prompt' => '', 'data-field' => 'entity_type']
    ) ?>

    <?= $form->field($model, 'attribute_name')->dropDownList(
        $model::getSearchableAttributes($model->entity_type),
        ['prompt' => '', 'data-field' => 'attribute']
    ) ?>

    <label><?= Module::t('facet', 'Custom values') ?></label>
    <div data-toolbar="facet-values-list">
        <div class="dropdown">
            <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                <?= Module::t('facet', 'Add value') ?>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                <?php foreach ($model->getValueTypesList() as $type => $name) : ?>
                    <li><a href="#" data-action="add" data-action-params='{"itemType":"<?= $type ?>"}'><?= $name ?></a></li>
                <?php endforeach ?>
            </ul>
        </div>
    </div>
    <br>
    <?= Html::hiddenInput('FacetValues') ?>
    <?= ListView::widget([
        'options' => ['id' => 'facet-values-list'],
        'dataProvider' => new ActiveDataProvider(['query' => $model->getValuesRelation()->orderBy('sort')]),
        'itemView' => function (FacetValue $item, $key, $index, ListView $widget) use ($model, $form) {
            if ($item instanceof EditableFacetValueInterface) {
                return $this->render($item->getEditView(), ['model' => $item, 'form' => $form, 'key' => $key, 'fieldConfig' => [
                    'namePrefix' => 'FacetValues',
                    'tabularIndex' => $index
                ]]);
            } else {
                return '';
            }
        },
        'toolbarView' => false,
        'sortable' => true,
        'viewParams' => ['form' => $form, 'fieldConfig' => ['namePrefix' => 'FacetValues']],
        'showOnEmpty' => true,
        'clientOptions' => ['addUrl' => Url::to(['add-value'])]
    ]); ?>

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
    var entityType = '[data-field="entity_type"]';
    var attribute = '[data-field="attribute"]';
    var multivalue = '[data-field="multivalue"]';
    var operator = '[data-field="operator"]';
    $(document).on('change', multivalue, function() {
        if ($(this).is(':checked')) {
            $('#{$formId}').find(operator).show();
        } else {
            $('#{$formId}').find(operator).hide();
        }
    });
    $(document).on('change', entityType, function() {
        $.ajax({
            url: 'attributes?entityType=' + $(this).val()
        })
        .done(function(data) {
            $(attribute).html(data);
        });
    });
    $(document).on('change', facetType, function() {
        $.pjax.reload({container: '#facet-form-cont', url: '{$url}', data: {type: $(this).val()}});
    });
JS;
$this->registerJs($script);
?>
