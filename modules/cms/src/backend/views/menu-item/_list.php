<?php

use im\base\widgets\ListViewAsset;
use im\cms\Module;
use im\tree\widgets\JsTree;
use im\tree\widgets\JsTreeToolbar;
use im\tree\widgets\TreeDetails;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use yii\web\JqueryAsset;
use yii\widgets\ActiveFormAsset;
use yii\widgets\PjaxAsset;

/* @var $this yii\web\View */
/* @var $menu im\cms\models\Menu */

ActiveFormAsset::register($this);
JqueryAsset::register($this);
PjaxAsset::register($this);
ListViewAsset::register($this);
$treeId = 'menu-items-tree';
$treeDetailsId = 'menu-item-details';
$confirmationModalId = 'confirmation-modal';
?>
<div class="row">
    <div class="col-xs-4">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $this->params['subtitle'] ?></h3>
                <div class="box-tools pull-right">
                    <form method="post" action="#">
                        <div class="input-group">
                            <input type="text" class="form-control input-sm tree-search">
                            <span class="input-group-btn">
                                <button class="btn btn-success btn-sm" type="button"><span class="fa fa-search" aria-hidden="true"></span></button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
            <div class="box-body">
                <?= JsTree::widget([
                    'id' => $treeId,
                    'treeDetails' => $treeDetailsId,
                    'confirmationModal' => $confirmationModalId,
                    'apiOptions' => [
                        'rootsUrl' => $menu ? Url::to(["/api/v1/menus/{$menu->id}/items/roots"]) : Url::to(['/api/v1/menu-items/roots']),
                        'childrenUrl' => Url::to(['/api/v1/menu-items/{id}/children']),
                        'createUrl' => Url::to(['menu-item/create-node', 'menu' => $menu->id]),
                        'editUrl' => Url::to(['menu-item/update', 'id' => '{id}']),
                        'updateUrl' => Url::to(['/api/v1/menu-items/{id}']),
                        'deleteUrl' => Url::to(['/api/v1/menu-items/{id}']),
                        'moveUrl' => Url::to(['/api/v1/menu-items/{id}/move']),
                        'searchUrl' => $menu ? Url::to(["/api/v1/menus/{$menu->id}/items/search"]) : Url::to(['/api/v1/menu-items/search']),
                        'searchableAttributes' => ['text', 'data.label'],
                        'attributesMap' => ['id' => 'id', 'text' => 'label', 'children' => 'hasChildren', 'str' => 'string']
                    ]
                ]); ?>
            </div>
            <div class="box-footer">
                <div class="box-tools pull-right">
                    <?php JsTreeToolbar::begin(['tree' => $treeId]); ?>
                    <button class="btn btn-sm btn-default" title="<?= Module::t('category', 'Create') ?>" data-toolbar-action="create"><span class="fa fa-plus" aria-hidden="true"></span></button>
                    <button class="btn btn-sm btn-default" title="<?= Module::t('category', 'Edit') ?>" data-toolbar-action="edit" data-not-selected-message="<?= Module::t('category', 'Please, select category to edit') ?>"><span class="fa fa-pencil" aria-hidden="true"></span></button>
                    <button class="btn btn-sm btn-default" title="<?= Module::t('category', 'Delete') ?>" data-toolbar-action="delete" data-not-selected-message="<?= Module::t('category', 'Please, select categories to delete') ?>"><span class="fa fa-trash-o" aria-hidden="true"></span></button>
                    <?php JsTreeToolbar::end(); ?>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
    <div class="col-xs-8">
        <?php TreeDetails::begin(['id' => $treeDetailsId]); ?>
        <?php TreeDetails::end(); ?>
    </div>
</div>
<?php
Modal::begin([
    'id' => 'confirmation-modal'
]); ?>
<button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
<?php Modal::end(); ?>