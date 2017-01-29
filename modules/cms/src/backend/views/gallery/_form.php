<?php

use im\base\widgets\ListView;
use im\cms\Module;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\cms\models\Gallery */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="gallery-form">

    <?php $form = ActiveForm::begin(['id' => 'template-form', 'fieldClass' => 'im\forms\widgets\ActiveField']); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'uploadedItems')->fileInput() ?>
    <?= Html::hiddenInput('uploadedItems') ?>
    <?= ListView::widget([
        'dataProvider' => new ActiveDataProvider([
            'query' => $model->itemsRelation()->orderBy('sort'),
            'pagination' => [
                'pageSize' => 100
            ]
        ]),
        'itemView' => '@im/cms/backend/views/gallery-item/_form',
        'sortable' => true,
        'mode' => ListView::MODE_GRID,
        'addLabel' => false,
        'viewParams' => ['form' => $form, 'fieldConfig' => ['namePrefix' => 'uploadedItems']]
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'), [
            'class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success',
            'name' => 'submit-button'
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>