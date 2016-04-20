<?php

namespace im\ecommerce\flow\step;

use im\flow\FlowContextInterface;
use im\flow\step\BaseControllerStep;

/**
 * Class FirstStep
 * @package im\ecommerce\flow\step
 */
class FirstStep extends BaseControllerStep
{
    /**
     * {@inheritdoc}
     */
    public function displayAction(FlowContextInterface $context)
    {
        return $this->render('@im/ecommerce/views/checkout/_first_step');
    }

    /**
     * {@inheritdoc}
     */
    public function forwardAction(FlowContextInterface $context)
    {
        return $this->complete();
    }
}