<?php

use im\cms\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $attributes array */

?>

<?php
$form = ActiveForm::begin(['id' => 'batch-settings-form']);
echo Html::checkbox("select_all", true, ['label' => Module::t('page', 'Select all'), 'class' => 'select-all-attributes']);
if (isset($attributes['model'])) { ?>
    <h4><?= Module::t('page', 'Main') ?></h4>
    <?php foreach($attributes['model'] as $attribute) { ?>
        <?= Html::checkbox("attributes[model][]", true, ['value' => $attribute->name, 'label' => $attribute->label]) ?>
    <?php }
}
if (isset($attributes['pageMeta'])) { ?>
    <h4><?= Module::t('page', 'Meta information') ?></h4>
    <?php foreach($attributes['pageMeta'] as $attribute) { ?>
        <?= Html::checkbox("attributes[pageMeta][]", true, ['value' => $attribute->name, 'label' => $attribute->label]) ?>
    <?php }
}
if (isset($attributes['openGraph'])) { ?>
    <h4><?= Module::t('page', 'Social Meta Tags') ?></h4>
    <h5><?= Module::t('page', 'Open Graph') ?></h5>
    <?php foreach($attributes['openGraph'] as $attribute) { ?>
        <?= Html::checkbox("attributes[openGraph][]", true, ['value' => $attribute->name, 'label' => $attribute->label]) ?>
    <?php }
}
echo '<br>';
echo Html::submitButton(Module::t('page', 'Update'), ['class' => 'btn btn-success']);
ActiveForm::end();
$this->registerJs("
    jQuery('#{$form->getId()} .select-all-attributes').on('click', function(e) {
        jQuery('#{$form->getId()}').find(':checkbox').prop('checked', this.checked);
        e.stopPropagation();
    });
");
?>