<?php

namespace im\flow\step;

use im\base\di\ContainerAwareInterface;
use im\base\di\ContainerAwareTrait;

/**
 * Class BaseContainerAwareStep
 * @package im\flow\Step
 */
abstract class BaseContainerAwareStep extends BaseStep implements ContainerAwareInterface
{
    use ContainerAwareTrait;
}
