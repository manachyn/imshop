<?php

namespace im\base\di;

use yii\di\Container;

/**
 * Interface ContainerAwareInterface
 * @package im\base\di
 */
interface ContainerAwareInterface
{
    /**
     * Sets the container.
     *
     * @param Container|null $container
     */
    public function setContainer(Container $container = null);
}

