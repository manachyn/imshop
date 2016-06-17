<?php

/**
 * Role create view.
 *
 * @var \yii\base\View $this View
 * @var \yii\base\DynamicModel $model Model
 * @var \vova07\themes\admin\widgets\Box $box Box widget instance
 * @var array $roleArray Roles array
 * @var array $ruleArray Rules array
 * @var array $permissionArray Permissions array
 */

use vova07\themes\admin\widgets\Box;
use vova07\blogs\Module;

$this->title = Module::t('rbac', 'BACKEND_ROLES_CREATE_TITLE');
$this->params['subtitle'] = Module::t('rbac', 'BACKEND_ROLES_CREATE_SUBTITLE');
$this->params['breadcrumbs'] = [
    [
        'label' => $this->title,
        'url' => ['index'],
    ],
    $this->params['subtitle']
]; ?>
<div class="row">
    <div class="col-sm-12">
        <?php $box = Box::begin(
            [
                'title' => $this->params['subtitle'],
                'renderBody' => false,
                'options' => [
                    'class' => 'box-primary'
                ],
                'bodyOptions' => [
                    'class' => 'table-responsive'
                ],
                'buttonsTemplate' => '{cancel}'
            ]
        );
        echo $this->render(
            '_form',
            [
                'model' => $model,
                'roleArray' => $roleArray,
                'ruleArray' => $ruleArray,
                'permissionArray' => $permissionArray,
                'box' => $box
            ]
        );
        Box::end(); ?>
    </div>
</div>