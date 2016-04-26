<?php

use im\base\widgets\Toolbar;
use im\cms\models\Page;
use im\cms\Module;
use app\themes\admin\widgets\Box;
use kartik\popover\PopoverX;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\jui\DatePicker;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel im\cms\models\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Module::t('page', 'Pages');
$this->params['subtitle'] = Module::t('module', 'Pages list');
$this->params['breadcrumbs'][] = $this->title;

$gridId = 'pages-grid';
$gridConfig = [
    'id' => $gridId,
    'tableOptions' => [
        'class' => 'table table-bordered table-hover'
    ],
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\CheckboxColumn'],
        ['attribute' => 'id', 'filter' => false],
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
            'value' => function ($model) {
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
                    Page::getStatusesList(),
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
        ]
    ],
];

$boxButtons = $actions = [];
$showActions = false;

//if (Yii::$app->user->can('BackendCreatePages')) {
    $boxButtons[] = '{create}';
//}
//if (Yii::$app->user->can('BackendUpdatePages')) {
    $actions[] = '{update}';
    $showActions = $showActions || true;
//}
//if (Yii::$app->user->can('BackendDeletePages')) {
    $boxButtons[] = '{batch-delete}';
    $actions[] = '{delete}';
    $showActions = $showActions || true;
//}

if ($showActions === true) {
    $gridConfig['columns'][] = [
        'class' => 'yii\grid\ActionColumn',
        'template' => implode(' ', $actions)
    ];
}
$boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null; ?>

<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= $this->params['subtitle'] ?></h3>
        <div class="box-tools pull-right">
            <?php Toolbar::begin(['grid' => $gridId]); ?>
                <!--<a href="<?= Url::to(['batch-delete']) ?>"
                   title="<?= Module::t('page', 'Delete selected') ?>"
                   data-toolbar-action="delete"
                   data-not-selected-message="<?= Module::t('page', 'Please, select items to delete') ?>"
                   data-confirmation-message="<?= Module::t('page', 'Are you sure you want to delete selected items?') ?>"
                   class="btn btn-sm btn-default">
                    <i class="fa fa-trash-o"></i>
                </a>
                <a href="<?= Url::to(['batch-update']) ?>"
                   data-toolbar-action="update"
                   data-not-selected-message="<?= Module::t('page', 'Please, select items to delete') ?>"
                   data-popover-title="<?= Module::t('page', 'Fields to update') ?>"
                   data-modal="remotePjaxModal"
                   class="btn btn-sm btn-default">
                    <i class="fa fa-edit"></i>
                </a>-->
                <a href="<?= Url::to(['create']) ?>"
                   title="<?= Module::t('page', 'Create') ?>" class="btn btn-sm btn-default">
                    <i class="fa fa-plus"></i>
                </a>
            <?php Toolbar::end(); ?>
        </div>
    </div>
    <div class="box-body">
        <?php Pjax::begin(); ?>
        <?= GridView::widget($gridConfig); ?>
        <?php Pjax::end(); ?>
    </div>
</div>

