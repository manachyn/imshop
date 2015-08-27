<?php

use im\search\backend\Module;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\search\models\Facet */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="index-form">

    <?php $form = ActiveForm::begin(['fieldClass' => 'im\forms\widgets\ActiveField']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'entity_type')->dropDownList(
        $model::getEntityTypesList(),
        ['prompt' => '']
    ) ?>

    <?= $form->field($model, 'searchableAttribute')->dropDownList(
        $model::getSearchableAttributes($model->entity_type),
        ['prompt' => '']
    ) ?>

    <?= $form->field($model, 'type')->dropDownList($model::getTypesList()) ?>

    <?= \im\base\widgets\RelationWidget::widget([
        'relation' => $model->getRanges(),
        'itemClass' => 'im\search\models\FacetRange',
        'itemView' => '/facet-range/_form',
        'form' => $form,
        'sortable' => true
    ]); ?>

    <?= Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'),
        ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>

</div>

<?php
$script = <<<JS
    var type = $('[name="Facet[entity_type]"]');
    var attribute = $('[name="Facet[searchableAttribute]"]');
    type.on('change', function() {
        $.ajax({
            url: 'attributes?entityType=' + $(this).val()
        })
        .done(function(data) {
            attribute.html(data);
        });
    });
JS;
$this->registerJs($script);
?>
