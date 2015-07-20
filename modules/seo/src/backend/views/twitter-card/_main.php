<?php

/* @var $this yii\web\View */
/* @var $model app\modules\seo\models\TwitterCard */
/* @var $form yii\widgets\ActiveForm */

?>

<?= !isset($attributes) || in_array('card', $attributes) ? $form->field($model, 'card')->textInput(['maxlength' => true]) : '' ?>

<?= !isset($attributes) || in_array('site', $attributes) ? $form->field($model, 'site')->textInput(['maxlength' => true]) : '' ?>

<?= !isset($attributes) || in_array('title', $attributes) ? $form->field($model, 'title')->textInput(['maxlength' => true]) : '' ?>

<?= !isset($attributes) || in_array('description', $attributes) ? $form->field($model, 'description')->textInput() : '' ?>

<?= !isset($attributes) || in_array('creator', $attributes) ? $form->field($model, 'creator')->textInput(['maxlength' => true]) : '' ?>

<?= !isset($attributes) || in_array('image', $attributes) ? $form->field($model, 'image')->textInput() : '' ?>
