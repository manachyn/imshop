<?php

namespace im\rbac\components;

/**
 * Interface AuthDataProviderInterface
 * @package im\rbac\components
 */
interface AuthDataProviderInterface
{
    /**
     * @return array
     */
    public function getAuthItems();

    /**
     * @return array
     */
    public function getAuthRules();
}
