<?php

/**
 * Rule create view.
 *
 * @var \yii\base\View $this View
 * @var \yii\base\Model $model Model
 * @var \vova07\themes\admin\widgets\Box $box Box widget instance
 */

use vova07\themes\admin\widgets\Box;
use vova07\blogs\Module;

$this->title = Module::t('rbac', 'BACKEND_RULES_CREATE_TITLE');
$this->params['subtitle'] = Module::t('rbac', 'BACKEND_RULES_CREATE_SUBTITLE');
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
                'box' => $box
            ]
        );
        Box::end(); ?>
    </div>
</div>