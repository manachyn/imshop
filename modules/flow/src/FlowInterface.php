<?php

namespace im\flow;

use im\flow\step\StepInterface;

/**
 * Interface FlowInterface
 * @package im\flow
 */
interface FlowInterface
{
    /**
     * Get scenario alias.
     *
     * @return string
     */
    public function getScenarioAlias();

    /**
     * Set scenario alias.
     *
     * @param string $scenarioAlias
     */
    public function setScenarioAlias($scenarioAlias);

    /**
     * Get a collection of steps.
     * Keys will be step names.
     *
     * @return StepInterface[]
     */
    public function getSteps();

    /**
     * Set steps.
     *
     * @param StepInterface[] $steps
     */
    public function setSteps(array $steps);

    /**
     * Get steps in correct order.
     *
     * @return StepInterface[]
     */
    public function getOrderedSteps();

    /**
     * Get first process step.
     *
     * @return StepInterface
     */
    public function getFirstStep();

    /**
     * Get last step.
     *
     * @return StepInterface
     */
    public function getLastStep();

    /**
     * Add step and name it.
     *
     * @param string        $name
     * @param StepInterface $step
     */
    public function addStep($name, StepInterface $step);

    /**
     * Remove step.
     *
     * @param string $name
     */
    public function removeStep($name);

    /**
     * Has step with given name?
     *
     * @param string $name
     *
     * @return bool
     */
    public function hasStep($name);

    /**
     * Count all steps.
     *
     * @return int
     */
    public function countSteps();

    /**
     * Get validator.
     *
     * @return FlowValidatorInterface
     */
    public function getValidator();

    /**
     * Set validator.
     *
     * @param FlowValidatorInterface $validator
     *
     * @return $this
     */
    public function setValidator(FlowValidatorInterface $validator);

    /**
     * Get redirection after complete.
     *
     * @return string
     */
    public function getRedirect();

    /**
     * Set redirection after complete.
     *
     * @param string $redirect
     */
    public function setRedirect($redirect);

    /**
     * Get redirection route params after complete.
     *
     * @return array
     */
    public function getRedirectParams();

    /**
     * Set redirection route params after complete.
     *
     * @param array $params
     */
    public function setRedirectParams(array $params);

    /**
     * Get display route.
     *
     * @return string
     */
    public function getDisplayRoute();

    /**
     * Set display route.
     *
     * @param string $route
     */
    public function setDisplayRoute($route);

    /**
     * Get additional display route parameters.
     *
     * @return array
     */
    public function getDisplayRouteParams();

    /**
     * Set additional display route params.
     *
     * @param array $params
     */
    public function setDisplayRouteParams(array $params);

    /**
     * Get forward route.
     *
     * @return string
     */
    public function getForwardRoute();

    /**
     * Set forward route.
     *
     * @param string $route
     */
    public function setForwardRoute($route);

    /**
     * Get additional forward route parameters.
     *
     * @return array
     */
    public function getForwardRouteParams();

    /**
     * Set additional forward route params.
     *
     * @param array $params
     */
    public function setForwardRouteParams(array $params);

    /**
     * Get step by index/order.
     *
     * @param int $index
     *
     * @return StepInterface
     */
    public function getStepByIndex($index);

    /**
     * Get step by name.
     *
     * @param string $index
     *
     * @return StepInterface
     */
    public function getStepByName($index);
}
