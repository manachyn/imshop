<?php

namespace im\rbac\models;

use yii\helpers\ArrayHelper;
use yii\rbac\Item;

/**
 * Class Permission
 * @package im\rbac\models
 */
class Permission extends AuthItem
{
    /**
     * @inheritdoc
     */
    public function getAvailableChildren()
    {
        return ArrayHelper::map($this->manager->getPermissions(), 'name', function (\yii\rbac\Permission $item) {
            return empty($item->description) ? $item->name : $item->name . ' (' . $item->description . ')';
        });
    }

    /**
     * @inheritdoc
     */
    protected function createItem($name)
    {
        return $this->manager->createPermission($name);
    }
}
