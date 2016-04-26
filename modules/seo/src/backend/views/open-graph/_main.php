<?php

/* @var $this yii\web\View */
/* @var $model im\seo\models\OpenGraph */
/* @var $form yii\widgets\ActiveForm */

?>

<?= !isset($attributes) || in_array('title', $attributes) ? $form->field($model, 'title')->textInput(['maxlength' => true]) : '' ?>

<?= !isset($attributes) || in_array('type', $attributes) ? $form->field($model, 'type')->textInput(['maxlength' => true]) : '' ?>

<?= !isset($attributes) || in_array('url', $attributes) ? $form->field($model, 'url')->textInput() : '' ?>

<?= !isset($attributes) || in_array('image', $attributes) ? $form->field($model, 'image')->textInput() : '' ?>

<?= !isset($attributes) || in_array('description', $attributes) ? $form->field($model, 'description')->textInput() : '' ?>

<?= !isset($attributes) || in_array('site_name', $attributes) ? $form->field($model, 'site_name')->textInput() : '' ?>

<?= !isset($attributes) || in_array('video', $attributes) ? $form->field($model, 'video')->textInput() : '' ?>
