<?php

namespace im\flow;

use im\flow\step\StepInterface;

/**
 * Interface FlowValidatorInterface
 * @package im\flow
 */
interface FlowValidatorInterface
{
    /**
     * Message to display on invalid.
     *
     * @param string $message
     *
     * @return FlowValidatorInterface
     */
    public function setMessage($message);

    /**
     * Return message.
     *
     * @return string
     */
    public function getMessage();

    /**
     * Set step name to go on error.
     *
     * @param string $stepName
     *
     * @return FlowValidatorInterface
     */
    public function setStepName($stepName);

    /**
     * Return step name to go on error.
     *
     * @return string
     */
    public function getStepName();

    /**
     * Check validation.
     *
     * @param FlowContextInterface $flowContext
     *
     * @return bool
     */
    public function isValid(FlowContextInterface $flowContext);

    /**
     * @param StepInterface $step
     *
     * @return ActionResult|Response|View
     */
    public function getResponse(StepInterface $step);
}
