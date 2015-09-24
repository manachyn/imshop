<?php

use im\search\models\IndexAttribute;
use im\search\Module;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\search\models\Index */
/* @var $attributes im\search\models\IndexAttribute[] */
/* @var $dataProvider yii\data\ArrayDataProvider */

$this->title = Module::t('index', 'Indexes');
$this->params['subtitle'] = Module::t('index', 'Index attributes');
$this->params['breadcrumbs'] = [['label' => $this->title, 'url' => ['index']], $this->params['subtitle']];
?>
<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->params['subtitle'] ?></h3>
        <div class="box-tools pull-right">
            <?= Html::a('<i class="fa fa-reply"></i>', ['index'], ['class' => 'btn btn-sm btn-default', 'title' => Module::t('module', 'Cancel')]) ?>
        </div>
    </div>
    <div class="box-body">
        <?php $form = ActiveForm::begin(); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'name',
                'label',
                [
                    'attribute' => 'indexable',
                    'format' => 'raw',
                    'value' => function (IndexAttribute $attribute, $key) use ($form) {
                        $fields = $form->field($attribute, "[$key]index_type")->hiddenInput()->label(false);
                        $fields .= $form->field($attribute, "[$key]name")->hiddenInput()->label(false);
                        $fields .= $form->field($attribute, "[$key]indexable")->checkbox([], false)->label(false);
                        return $fields;
                    },
                ]
            ],
        ]); ?>

        <?= Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>

