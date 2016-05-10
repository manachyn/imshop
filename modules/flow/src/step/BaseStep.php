<?php

namespace im\flow\step;

use im\flow\FlowContextInterface;

/**
 * Class BaseStep
 * @package im\flow\step
 */
abstract class BaseStep implements StepInterface
{
    /**
     * Step name in current scenario.
     *
     * @var string
     */
    protected $name;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function forwardAction(FlowContextInterface $context)
    {
        return $this->complete();
    }

    /**
     * {@inheritdoc}
     */
    public function isActive()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function complete()
    {
        return new ActionResult();
    }

    /**
     * {@inheritdoc}
     */
    public function proceed($nextStepName)
    {
        return new ActionResult($nextStepName);
    }
}

