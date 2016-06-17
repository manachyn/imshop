<?php

namespace im\rbac\rules;

use yii\rbac\Rule;

/**
 * Class AuthorRule
 * @package im\rbac\rules
 */
class AuthorRule extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'author';

    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        return isset($params['model']) ? $params['model']['author_id'] == $user : false;
    }
}
