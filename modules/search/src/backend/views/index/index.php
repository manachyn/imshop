<?php

use im\search\Module;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel im\search\models\IndexSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('index', 'Indexes');
$this->params['subtitle'] = Module::t('index', 'Indexes list');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->params['subtitle'] ?></h3>
        <div class="box-tools pull-right">
            <?= Html::a('<i class="fa fa-plus"></i>', ['create'], ['class' => 'btn btn-sm btn-default']) ?>
        </div>
    </div>

    <div class="box-body">
        <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                'name',
                'status',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {attributes} {delete}',
                    'buttons' => [
                        'attributes' => function ($url) {
                                $options = [
                                    'title' => Module::t('index', 'Attributes'),
                                    'aria-label' => Module::t('index', 'Attributes'),
                                    'data-pjax' => '0',
                                ];
                                return Html::a('<span class="glyphicon glyphicon-cog"></span>', $url, $options);
                            },
                    ],
                ]
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
