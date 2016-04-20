<?php

namespace im\base\di;

use yii\di\ServiceLocator;

/**
 * Interface ServiceLocatorAwareInterface
 * @package im\base\di
 */
interface ServiceLocatorAwareInterface
{
    /**
     * Sets the service locator.
     *
     * @param ServiceLocator|null $serviceLocator
     */
    public function setServiceLocator(ServiceLocator $serviceLocator = null);
}
