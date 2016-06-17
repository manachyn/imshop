<?php

namespace im\rbac\rules;

use Yii;
use yii\rbac\Rule;

/**
 * Class GroupRule
 * @package im\rbac\rules
 */
class GroupRule extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'group';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            $role = Yii::$app->user->identity->role;

            if ($item->name === 'superadmin') {
                return $role === $item->name;
            } elseif ($item->name === 'admin') {
                return $role === $item->name || $role === 'superadmin';
            } elseif ($item->name === 'user') {
                return $role === $item->name || $role === 'superadmin' || $role === 'admin';
            }
        }

        return false;
    }
}

