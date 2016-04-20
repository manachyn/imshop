<?php

namespace im\flow\step;

use im\flow\FlowContextInterface;
use yii\base\View;
use yii\web\Response;

/**
 * Interface StepInterface
 * @package im\flow\step
 */
interface StepInterface
{
    /**
     * Get step name in current scenario.
     *
     * @return string
     */
    public function getName();

    /**
     * Set step name.
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * Display action.
     *
     * @param FlowContextInterface $context
     *
     * @return ActionResult|Response|string
     */
    public function displayAction(FlowContextInterface $context);

    /**
     * Forward action.
     *
     * @param FlowContextInterface $context
     *
     * @return null|ActionResult|Response|string
     */
    public function forwardAction(FlowContextInterface $context);

    /**
     * Is step active in process?
     *
     * @return bool
     */
    public function isActive();

    /**
     * Proceeds to the next step.
     *
     * @return ActionResult
     */
    public function complete();

    /**
     * Proceeds to the given step.
     *
     * @param string $nextStepName
     *
     * @return ActionResult
     */
    public function proceed($nextStepName);
}
