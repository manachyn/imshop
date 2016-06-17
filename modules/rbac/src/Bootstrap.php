<?php

namespace im\rbac;

use im\rbac\components\AuthDataProviderInterface;

/**
 * Class Bootstrap
 * @package im\rbac
 */
class Bootstrap implements AuthDataProviderInterface
{
    /**
     * @inheritdoc
     */
    public function getAuthItems()
    {
        $config = require(__DIR__ . '/config/rbac.php');

        return $config['items'];
    }

    /**
     * @inheritdoc
     */
    public function getAuthRules()
    {
        $config = require(__DIR__ . '/config/rbac.php');

        return $config['rules'];
    }
}

