<?php

namespace im\rbac\backend\controllers;

use im\rbac\Module;
use Yii;
use yii\rbac\Permission;
use yii\web\NotFoundHttpException;
use yii\rbac\Item;

/**
 * Class PermissionController
 * @package im\rbac\backend\controllers
 */
class PermissionController extends ItemBaseController
{
    /**
     * @var string
     */
    protected $modelClass = 'im\rbac\models\Permission';

    /**
     * @var int
     */
    protected $type = Item::TYPE_PERMISSION;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->successCreate = Module::t('permission', 'Permission has been successfully created.');
        $this->successUpdate = Module::t('permission', 'Permission has been successfully saved.');
        $this->successDelete = Module::t('permission', 'Permission has been successfully deleted.');
    }

    /**
     * @inheritdoc
     */
    protected function getItem($name)
    {
        $item = Yii::$app->authManager->getPermission($name);

        if ($item instanceof Permission) {
            return $item;
        }

        throw new NotFoundHttpException;
    }
}
