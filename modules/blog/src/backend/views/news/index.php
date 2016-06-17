<?php

use im\blog\models\News;
use im\blog\Module;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel im\blog\models\NewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('news', 'News');
$this->params['subtitle'] = Module::t('news', 'News list');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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
                'id',
                'title',
                [
                    'attribute' => 'slug',
                    'format' => 'html',
                    'value' => function ($model) {
                        return Html::a($model['slug'], ['update', 'id' => $model['id']], ['data-pjax' => 0]);
                    }
                ],
                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'value' => function (News $model) {
                        if ($model->status === $model::STATUS_PUBLISHED) {
                            $class = 'label-success';
                        } elseif ($model->status === $model::STATUS_UNPUBLISHED) {
                            $class = 'label-warning';
                        } else {
                            $class = 'label-danger';
                        }
                        return '<span class="label ' . $class . '">' . $model->getStatus() . '</span>';
                    },
                    'filter' => Html::activeDropDownList(
                        $searchModel,
                        'status',
                        News::getStatusesList(),
                        ['class' => 'form-control', 'prompt' => Module::t('page', 'Select status')]
                    )
                ],
                [
                    'attribute' => 'created_at',
                    'format' => 'date',
                    'filter' => DatePicker::widget(
                        [
                            'model' => $searchModel,
                            'attribute' => 'created_at',
                            'options' => [
                                'class' => 'form-control'
                            ],
                            'clientOptions' => [
                                'dateFormat' => 'dd.mm.yy',
                            ]
                        ]
                    )
                ],
                [
                    'attribute' => 'updated_at',
                    'format' => 'date',
                    'filter' => DatePicker::widget(
                        [
                            'model' => $searchModel,
                            'attribute' => 'updated_at',
                            'options' => [
                                'class' => 'form-control'
                            ],
                            'clientOptions' => [
                                'dateFormat' => 'dd.mm.yy',
                            ]
                        ]
                    )
                ],
                ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}']
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>