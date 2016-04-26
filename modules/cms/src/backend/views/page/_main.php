<?php

use im\cms\Module;
use vova07\imperavi\Widget as Imperavi;

/* @var $this yii\web\View */
/* @var $model im\cms\models\Page */
/* @var $form yii\widgets\ActiveForm */

?>

<?= !isset($attributes) || in_array('title', $attributes) ? $form->field($model, 'title')->textInput(['maxlength' => 100]) : '' ?>

<?= !isset($attributes) || in_array('slug', $attributes) ? $form->field($model, 'slug')->textInput(['maxlength' => 100]) : '' ?>

<?= !isset($attributes) || in_array('content', $attributes) ? $form->field($model, 'content')->widget(
    Imperavi::className(),
    [
        'settings' => [
            'minHeight' => 300,
//                    'imageGetJson' => Url::to(['/blogs/default/imperavi-get']),
//                    'imageUpload' => Url::to(['/blogs/default/imperavi-image-upload']),
//                    'fileUpload' => Url::to(['/blogs/default/imperavi-file-upload'])
        ]
    ]
) : '' ?>

<?= !isset($attributes) || in_array('status', $attributes) ? $form->field($model, 'status')->dropDownList(
    $model::getStatusesList(),
    ['prompt' => Module::t('page', 'Select status')]
) : ''?>