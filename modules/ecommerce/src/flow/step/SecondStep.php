<?php

namespace im\ecommerce\flow\step;

use im\flow\FlowContextInterface;
use im\flow\step\BaseControllerStep;

/**
 * Class SecondStep
 * @package im\ecommerce\flow\step
 */
class SecondStep extends BaseControllerStep
{
    /**
     * {@inheritdoc}
     */
    public function displayAction(FlowContextInterface $context)
    {
        return $this->render('@im/ecommerce/views/checkout/_second_step');
    }

    /**
     * {@inheritdoc}
     */
    public function forwardAction(FlowContextInterface $context)
    {
        return $this->complete();
    }
}