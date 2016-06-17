<?php

namespace im\rbac\backend\controllers;

use im\rbac\Module;
use Yii;
use yii\rbac\Role;
use yii\web\NotFoundHttpException;
use yii\rbac\Item;

/**
 * Class RoleController
 * @package im\rbac\backend\controllers
 */
class RoleController extends ItemBaseController
{
    /**
     * @var string
     */
    protected $modelClass = 'im\rbac\models\Role';

    /**
     * @var int
     */
    protected $type = Item::TYPE_ROLE;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->successCreate = Module::t('role', 'Role has been successfully created.');
        $this->successUpdate = Module::t('role', 'Role has been successfully saved.');
        $this->successDelete = Module::t('role', 'Role has been successfully deleted.');
    }

    /**
     * @inheritdoc
     */
    protected function getItem($name)
    {
        $item = Yii::$app->authManager->getRole($name);

        if ($item instanceof Role) {
            return $item;
        }

        throw new NotFoundHttpException;
    }
}
