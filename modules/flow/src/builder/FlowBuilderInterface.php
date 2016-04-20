<?php

namespace im\flow\builder;

use im\flow\FlowInterface;
use im\flow\FlowValidatorInterface;
use im\flow\scenario\FlowScenarioInterface;
use im\flow\step\StepInterface;

/**
 * Interface FlowBuilderInterface
 * @package im\flow\builder
 */
interface FlowBuilderInterface
{
    /**
     * Build flow by adding steps defined in scenario.
     *
     * @param FlowScenarioInterface $scenario
     *
     * @return FlowInterface
     */
    public function build(FlowScenarioInterface $scenario);

    /**
     * Add a step with given name.
     *
     * @param string $name
     * @param string|StepInterface $step Step alias or instance
     */
    public function add($name, $step);

    /**
     * Remove step with given name.
     *
     * @param string $name
     */
    public function remove($name);

    /**
     * Check whether or not flow has given step.
     *
     * @param string $name
     *
     * @return bool
     */
    public function has($name);

    /**
     * Set display route.
     *
     * @param string $route
     */
    public function setDisplayRoute($route);

    /**
     * Set additional forward route params.
     *
     * @param array $params
     */
    public function setDisplayRouteParams(array $params);

    /**
     * Set forward route.
     *
     * @param string $route
     */
    public function setForwardRoute($route);

    /**
     * Set additional forward route params.
     *
     * @param array $params
     */
    public function setForwardRouteParams(array $params);

    /**
     * Set redirection route after completion.
     *
     * @param string $redirect
     */
    public function setRedirect($redirect);

    /**
     * Set redirection route params.
     *
     * @param array $params
     */
    public function setRedirectParams(array $params);

    /**
     * Validation of flow, if returns false, flow is suspended.
     *
     * @param \Closure|FlowValidatorInterface $validator
     */
    public function validate($validator);

    /**
     * Register new step.
     *
     * @param string $alias
     * @param StepInterface $step
     */
    public function registerStep($alias, StepInterface $step);

    /**
     * Load step.
     *
     * @param string $alias
     *
     * @return StepInterface
     */
    public function loadStep($alias);
}
