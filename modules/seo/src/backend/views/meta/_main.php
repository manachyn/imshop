<?php

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $model im\seo\models\Meta */

?>

<div class="row">

    <div class="col-sm-12">
        <?= !isset($attributes) || in_array('meta_title', $attributes) ? $form->field($model, 'meta_title')->textInput(['maxlength' => true]) : '' ?>
    </div>

</div>

<div class="row">

    <div class="col-sm-6">
        <?= !isset($attributes) || in_array('meta_description', $attributes) ? $form->field($model, 'meta_description')->textarea(['maxlength' => true]) : '' ?>
    </div>

    <div class="col-sm-6">
        <?= !isset($attributes) || in_array('meta_keywords', $attributes) ? $form->field($model, 'meta_keywords')->textarea(['maxlength' => true]) : '' ?>
    </div>

</div>

<?= !isset($attributes) || in_array('custom_meta', $attributes) ? $form->field($model, 'custom_meta')->textarea() : '' ?>

<?= !isset($attributes) || in_array('meta_robots', $attributes) ? $form->field($model, 'metaRobotsDirectives')->checkboxList(
    $model::getMetaRobotsDirectivesList()
) : '' ?>

