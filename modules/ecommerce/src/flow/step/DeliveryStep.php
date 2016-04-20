<?php

namespace im\ecommerce\flow\step;

use im\flow\FlowContextInterface;
use im\flow\step\BaseServiceLocatorAwareStep;

/**
 * Class DeliveryStep
 * @package im\ecommerce\flow\step
 */
class DeliveryStep extends BaseServiceLocatorAwareStep
{
    /**
     * {@inheritdoc}
     */
    public function displayAction(FlowContextInterface $context)
    {
        $address = $context->getStorage()->get('delivery.address');

        return $this->serviceLocator->get('view')->render('@im/ecommerce/views/checkout/_delivery_step', [
            'address' => $address,
            'context' => $context
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function forwardAction(FlowContextInterface $context)
    {
//        $request = $context->getRequest();
//        $form = $this->createAddressForm();
//
//        if ($form->handleRequest($request)->isValid()) {
//            $context->getStorage()->set('delivery.address', $form->getData());
//
//            return $this->complete();
//        }
//
//        return $this->container->get('view')->render('@im/ecommerce/views/checkout/_delivery_step', [
//            'context' => $context
//        ]);

        // TODO Handle form

        return $this->complete();
    }
}