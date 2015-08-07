<?php

use im\catalog\models\ProductType;
use im\eav\widgets\EAVEditor;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model im\catalog\models\Product */
/* @var $form yii\widgets\ActiveForm */

$attributes = $model->getEAttributes();
if (!$attributes) {
    $attributes = $model->getAvailableEAttributes();
}
?>

<?= $form->field($model, 'type_id')->dropDownList(
    ArrayHelper::map(ProductType::find()->asArray()->orderBy('name')->all(), 'id', 'name'),
    ['prompt' => '']
) ?>

<?= EAVEditor::widget(['attributes' => $attributes, 'form' => $form, 'model' => $model, 'options' => ['id' => 'eav-editor']]) ?>

<?php
$script = <<<JS
    var type = $('[name="Product[type_id]"]');
    var editor = $('#eav-editor').eavEditor();
    type.on('change', function() {
        $.ajax({
            url: '/api/v1/product-types/' + $(this).val() + '/attributes',
            dataType: 'json'
        })
        .done(function(data) {
            if (data) {
                var attributes = $.map(data, function(attr) {
                    return attr.id;
                });
                if (attributes.length > 0) {
                    editor.setAttributes(attributes);
                }
            }
        });
    });
JS;
$this->registerJs($script);
?>





