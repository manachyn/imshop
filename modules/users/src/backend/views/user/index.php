<?php

use im\users\models\User;
use im\users\Module;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel im\users\backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('user', 'Users');
$this->params['subtitle'] = Module::t('user', 'Users list');
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
                ['class' => 'yii\grid\SerialColumn'],
                'id',
                'username',
                'email:email',
                [
                    'attribute' => 'first_name',
                    'value' => 'profile.first_name'
                ],
                [
                    'attribute' => 'last_name',
                    'value' => 'profile.last_name'
                ],
                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'value' => function (User $model) {
                        if ($model->isActive()) {
                            $class = 'label-success';
                        } elseif ($model->isNotConfirmed()) {
                            $class = 'label-warning';
                        } elseif ($model->isBlocked()) {
                            $class = 'label-danger';
                        } else {
                            $class = 'label-default';
                        }
                        return '<span class="label ' . $class . '">' . $model->getStatus() . '</span>';
                    },
                    'filter' => Html::activeDropDownList($searchModel, 'status', User::getStatusesList(),
                        ['class' => 'form-control', 'prompt' => '']
                    )
                ],
                [
                    'attribute' => 'created_at',
                    'format' => 'date',
                    'filter' => false
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {assign} {delete}',
                    'urlCreator' => function ($action, $model, $key, $index) {
                        return $action == 'assign' ? ['/rbac/assignment/assign', 'id' => $model['id']]
                            : [$action, 'id' => $model['id']];
                    },
                    'buttons' => [
                        'assign' => function ($url) {
                            $options = [
                                'title' => Module::t('user', 'Roles'),
                                'aria-label' => Module::t('user', 'Roles'),
                                'data-pjax' => '0'
                            ];
                            return Html::a('<span class="glyphicon glyphicon-user"></span>', $url, $options);
                        }
                    ]
                ]
            ],
        ]); ?>
        <?php Pjax::end(); ?>
    </div>
</div>
