<?php

use im\base\widgets\ListView;
use im\base\widgets\ListViewAsset;
use im\search\components\query\facet\EditableFacetValueInterface;
use im\search\models\FacetValue;
use im\search\models\SearchableTypesFacet;
use im\search\Module;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model SearchableTypesFacet */
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

    <label><?= Module::t('facet', 'Custom values') ?></label>
    <div data-toolbar="facet-values-list">
        <button class="btn btn-success" type="button" data-action="add" data-action-params='{"itemType":"searchable_types_facet_term"}'>
            <?= Module::t('facet', 'Add value') ?>
        </button>
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
    $(document).on('change', facetType, function() {
        $.pjax.reload({container: '#facet-form-cont', url: '{$url}', data: {type: $(this).val()}});
    });
JS;
$this->registerJs($script);
?>
