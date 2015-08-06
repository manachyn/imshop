<?php

use im\search\backend\Module;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model im\search\models\Index */
/* @var $attributes im\search\models\IndexAttribute[] */

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

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Label</th>
                    <th>Indexable</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($attributes as $index => $item): ?>
                <tr>
                    <td>
                        <?= $item->name ?>
                    </td>
                    <td>
                        <?= $item->label ?>
                    </td>
                    <td>
                        <?= $form->field($item, "[$item->id]enabled")->checkbox([], false)->label(false); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?= Html::submitButton($model->isNewRecord ? Module::t('module', 'Create') : Module::t('module', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-primary' : 'btn btn-success']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</div>

