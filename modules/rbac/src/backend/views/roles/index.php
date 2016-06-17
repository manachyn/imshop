<?php

/**
 * Roles list view.
 *
 * @var \yii\base\View $this View
 * @var \yii\data\ArrayDataProvider $provider Data provider
 */

use vova07\themes\admin\widgets\Box;
use vova07\themes\admin\widgets\GridView;
use vova07\blogs\Module;
use yii\grid\ActionColumn;
use yii\grid\CheckboxColumn;

$this->title = Module::t('rbac', 'BACKEND_ROLES_INDEX_TITLE');
$this->params['subtitle'] = Module::t('rbac', 'BACKEND_ROLES_INDEX_SUBTITLE');
$this->params['breadcrumbs'] = [
    $this->title
];
$gridId = 'roles-grid';
$gridConfig = [
    'id' => $gridId,
    'dataProvider' => $provider,
    'columns' => [
        [
            'class' => CheckboxColumn::classname()
        ],
        'name',
        'description',
        'ruleName',
        'data',
        'createdAt:date',
        'updatedAt:date'
    ]
];

$boxButtons = $actions = [];
$showActions = false;

if (Yii::$app->user->can('BCreateRoles')) {
    $boxButtons[] = '{create}';
}
if (Yii::$app->user->can('BUpdateRoles')) {
    $actions[] = '{update}';
    $showActions = $showActions || true;
}
if (Yii::$app->user->can('BDeleteRoles')) {
    $boxButtons[] = '{batch-delete}';
    $actions[] = '{delete}';
    $showActions = $showActions || true;
}

if ($showActions === true) {
    $gridConfig['columns'][] = [
        'class' => ActionColumn::className(),
        'template' => implode(' ', $actions)
    ];
}

$boxButtons = !empty($boxButtons) ? implode(' ', $boxButtons) : null; ?>

<div class="row">
    <div class="col-xs-12">
        <?php Box::begin([
            'title' => $this->params['subtitle'],
            'bodyOptions' => [
                'class' => 'table-responsive'
            ],
            'batchParam' => 'names',
            'buttonsTemplate' => $boxButtons,
            'grid' => $gridId,
        ]); ?>
        <?= GridView::widget($gridConfig); ?>
        <?php Box::end(); ?>
    </div>
</div>