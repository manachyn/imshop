<?php

namespace im\base\di;

use yii\di\ServiceLocator;

/**
 * Class ServiceLocatorAwareTrait
 * @package im\base\di
 */
trait ServiceLocatorAwareTrait
{
    /**
     * @var ServiceLocator
     */
    protected $serviceLocator;

    /**
     * Sets the service locator.
     *
     * @param ServiceLocator $serviceLocator
     */
    public function setServiceLocator(ServiceLocator $serviceLocator = null)
    {
        $this->serviceLocator = $serviceLocator;
    }
}
