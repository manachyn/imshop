<?php

use im\search\backend\Module;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel im\search\models\Facet */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('facet', 'Facets');
$this->params['subtitle'] = Module::t('facet', 'Facets list');
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
                ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}']
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
