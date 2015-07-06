<?php

use im\cms\Module;

/* @var $this yii\web\View */
/* @var $model im\cms\models\Page */
/* @var $form yii\widgets\ActiveForm */

$layouts = $model->getAvailableLayoutsList();

?>

<?php if ($layouts) {

    echo !isset($attributes) || in_array('layout_id', $attributes) ? $form->field(
        $model,
        'layout_id',
        ['labelOptions' => ['label' => Module::t('page', 'Layout')]]
    )->dropDownList(
        $layouts,
        ['prompt' => $model->getDefaultLayout()->getName()]
    ) : '';
    
} ?>


