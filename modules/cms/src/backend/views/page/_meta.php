<?php

/* @var $this yii\web\View */
/* @var $model im\cms\models\PageMeta */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="row">

    <div class="col-sm-12">
        <?= !isset($attributes) || in_array('meta_title', $attributes) ? $form->field($model, 'meta_title') : '' ?>
    </div>

</div>

<div class="row">

    <div class="col-sm-6">
        <?= !isset($attributes) || in_array('meta_keywords', $attributes) ? $form->field($model, 'meta_keywords')->textarea() : '' ?>
    </div>

    <div class="col-sm-6">
        <?= !isset($attributes) || in_array('meta_description', $attributes) ? $form->field($model, 'meta_description')->textarea() : '' ?>
    </div>

</div>

<?= !isset($attributes) || in_array('custom_meta', $attributes) ? $form->field($model, 'custom_meta')->textarea() : '' ?>

<?= !isset($attributes) || in_array('meta_robots', $attributes) ? $form->field($model, 'meta_robots')->checkboxList(
    $model::getMetaRobotsDirectivesList()
) : '' ?>

