<?php

use app\modules\tasks\models\Task;
use app\modules\tasks\Module;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\tasks\models\TaskSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('task', 'Tasks');
$this->params['subtitle'] = Module::t('task', 'Tasks list');
$this->params['breadcrumbs'][] = $this->title;
$statusColor = [
    Task::STATUS_NEW => 'primary',
    Task::STATUS_QUEUED => 'warning',
    Task::STATUS_IN_PROGRESS => 'info',
    Task::STATUS_COMPLETED => 'success',
    Task::STATUS_FAILED=> 'danger'
]
?>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->params['subtitle'] ?></h3>
    </div>
    <div class="box-body">
        <?php Pjax::begin(); ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'name',
                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'value' => function ($model) use($statusColor) {
                            $class = isset($statusColor[$model->status]) ? 'label-' . $statusColor[$model->status] : '';
                            return '<span class="label ' . $class . '">' . $model->statusString . '</span>';
                        },
                    'filter' => Task::getStatusesList()
                ],
                [
                    'attribute' => 'created_at',
                    'value' => 'created_at',
                    'format' => ['datetime', 'short'],
                    'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'attribute' => 'created_at',
                            'options' => [
                                'class' => 'form-control'
                            ]
                        ]),
                ],
                ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}']
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
