<?php

namespace im\ecommerce\flow\scenario;

use im\ecommerce\flow\step\DeliveryStep;
use im\flow\builder\FlowBuilderInterface;
use im\flow\scenario\FlowScenarioInterface;

/**
 * Class CheckoutScenario
 * @package im\ecommerce\flow
 */
class CheckoutScenario implements FlowScenarioInterface
{
    /**
     * {@inheritdoc}
     */
    public function build(FlowBuilderInterface $builder)
    {
        $builder
            ->add('delivery', new DeliveryStep())
        ;
    }
}