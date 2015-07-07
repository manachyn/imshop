<?php

use app\modules\users\models\User;
use app\modules\users\Module;
use app\themes\admin\widgets\Box;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\users\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('users', 'Users');
$this->params['subtitle'] = Module::t('users', 'Users list');
$this->params['breadcrumbs'] = [$this->title];

$gridId = 'users-grid';
$gridConfig = [
    'id' => $gridId,
    'tableOptions' => [
        'class' => 'table table-bordered table-hover'
    ],
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\CheckboxColumn'],
        'id',
        [
            'attribute' => 'username',
            'format' => 'html',
            'value' => function ($model) {
                    return Html::a($model['username'], ['update', 'id' => $model['id']], ['data-pjax' => 0]);
                }
        ],
        'email:email',
        ['attribute' => 'first_name', 'value' => 'profile.first_name'],
        ['attribute' => 'last_name', 'value' => 'profile.last_name'],
        [
            'attribute' => 'status',
            'format' => 'html',
            'value' => function ($model) {
                    if ($model->status === $model::STATUS_ACTIVE) {
                        $class = 'label-success';
                    } elseif ($model->status === $model::STATUS_INACTIVE) {
                        $class = 'label-warning';
                    } else {
                        $class = 'label-danger';
                    }
                    return '<span class="label ' . $class . '">' . $model->getStatus() . '</span>';
                },
            'filter' => Html::activeDropDownList(
                    $searchModel,
                    'status',
                    User::getStatusesList(),
                    ['class' => 'form-control', 'prompt' => Module::t('users', 'Select status')]
                )
        ],
        [
            'attribute' => 'role',
            'filter' => Html::activeDropDownList(
                    $searchModel,
                    'role',
                    User::getRolesList(),
                    ['class' => 'form-control', 'prompt' => Module::t('users', 'Select role')]
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
        ]
    ],
];

$boxButtons = $actions = [];
$showActions = false;

if (Yii::$app->user->can('BackendCreateUsers')) {
    $boxButtons[] = '{create}';
}
if (Yii::$app->user->can('BackendUpdateUsers')) {
    $actions[] = '{update}';
    $showActions = $showActions || true;
}
if (Yii::$app->user->can('BackendDeleteUsers')) {
    $boxButtons[] = '{batch-delete}';
    $actions[] = '{delete}';
    $showActions = $showActions || true;
}

if ($showActions === true) {
    $gridConfig['columns'][] = [
        'class' => 'yii\grid\ActionColumn',
        'template' => implode(' ', $actions)
    ];
}
$boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null; ?>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->params['subtitle'] ?></h3>
        <div class="box-tools pull-right">
            <?= Html::a('<i class="fa fa-plus"></i>', ['create'], ['class' => 'btn btn-sm btn-default']) ?>
        </div>
    </div>

    <div class="box-body">
        <?php Pjax::begin(); ?>
        <?= GridView::widget($gridConfig); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
