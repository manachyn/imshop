<?php

use im\base\widgets\RelationWidget;
use im\base\widgets\RelationWidgetAsset;
use im\search\backend\Module;
use im\search\models\Facet;
use im\search\models\IntervalFacet;
use im\search\models\RangeFacet;
use im\search\models\TermsFacet;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model Facet|TermsFacet|RangeFacet|IntervalFacet */
/* @var $form yii\widgets\ActiveForm */

RelationWidgetAsset::register($this);
?>

<?php Pjax::begin(['id' => 'facet-form']) ?>

<div class="facet-form">

    <?php $form = ActiveForm::begin(['fieldClass' => 'im\forms\widgets\ActiveField']); ?>

    <?php if ($model->isNewRecord) :
        echo $form->field($model, 'type')->dropDownList($model::getTypesList(), ['data-field' => 'type']) ?>
    <?php endif ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'entity_type')->dropDownList(
        $model::getEntityTypesList(),
        ['prompt' => '', 'data-field' => 'entity_type']
    ) ?>

    <?= $form->field($model, 'searchableAttribute')->dropDownList(
        $model::getSearchableAttributes($model->entity_type),
        ['prompt' => '', 'data-field' => 'searchable-attribute']
    ) ?>

    <?php if ($model instanceof IntervalFacet) :
        echo $form->field($model, 'from')->textInput(['maxlength' => true]);
        echo $form->field($model, 'to')->textInput(['maxlength' => true]);
        echo $form->field($model, 'interval')->textInput(['maxlength' => true]);
    endif ?>

    <?php if ($model instanceof RangeFacet) : ?>
        <label><?= Module::t('facet', 'Ranges') ?></label>
        <?= Html::hiddenInput('FacetRange') ?>
        <?= RelationWidget::widget([
            'relation' => $model->getRanges(),
            'modelClass' => 'im\search\models\FacetRange',
            'modelView' => '@im/search/backend/views/facet-range/_form',
            'form' => $form,
            'sortable' => true,
            'addLabel' => Module::t('facet', 'Add range')
        ]);
    elseif ($model instanceof TermsFacet) : ?>
        <label><?= Module::t('facet', 'Terms') ?></label>
        <?= Html::hiddenInput('FacetTerm') ?>
        <?= RelationWidget::widget([
            'relation' => $model->getTermsRelation()->orderBy('sort'),
            'modelClass' => 'im\search\models\FacetTerm',
            'modelView' => '@im/search/backend/views/facet-term/_form',
            'form' => $form,
            'sortable' => true,
            'addLabel' => Module::t('facet', 'Add term')
        ]);
    endif ?>

    <?= Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>

</div>

<?php Pjax::end() ?>

<?php
$url = Url::to(['create']);
$script = <<<JS
    var facetType = '[data-field="type"]';
    var entityType = '[data-field="entity_type"]';
    var attribute = '[data-field="searchable-attribute"]';
    $(document).on('change', entityType, function() {
        $.ajax({
            url: 'attributes?entityType=' + $(this).val()
        })
        .done(function(data) {
            $(attribute).html(data);
        });
    });
    $(document).on('change', facetType, function() {
        $.pjax.reload({container: '#facet-form', url: '{$url}', data: {type: $(this).val()}});
    });
JS;
$this->registerJs($script);
?>
