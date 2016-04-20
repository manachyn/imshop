<?php

namespace im\flow\step;

use im\base\di\ServiceLocatorAwareInterface;
use im\base\di\ServiceLocatorAwareTrait;

/**
 * Class BaseServiceLocatorAwareStep
 * @package im\flow\step
 */
abstract class BaseServiceLocatorAwareStep extends BaseStep implements ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;
}
