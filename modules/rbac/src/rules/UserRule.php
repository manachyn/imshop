<?php

namespace im\rbac\rules;

use Yii;
use yii\rbac\Rule;

/**
 * Class UserRule
 * @package im\rbac\rules
 */
class UserRule extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'user';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        return !Yii::$app->user->isGuest;
    }
}

