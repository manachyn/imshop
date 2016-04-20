<?php

namespace im\base\di;

use yii\di\Container;

/**
 * Class ContainerAwareTrait
 * @package im\base\di
 */
trait ContainerAwareTrait
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * Sets the container associated with this Controller.
     *
     * @param Container $container
     */
    public function setContainer(Container $container = null)
    {
        $this->container = $container;
    }
}

