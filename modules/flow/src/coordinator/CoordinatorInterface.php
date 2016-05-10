<?php

namespace im\flow\coordinator;

use im\flow\scenario\FlowScenarioInterface;
use yii\web\Response;

/**
 * Interface CoordinatorInterface
 * @package im\flow
 */
interface CoordinatorInterface
{
    /**
     * Start scenario, should redirect to first step.
     *
     * @param string $scenarioAlias
     * @param array $queryParameters
     * @return Response
     */
    public function start($scenarioAlias, array $queryParameters = []);

    /**
     * Display step.
     *
     * @param string $scenarioAlias
     * @param string $stepName
     * @param array $queryParameters
     *
     * @return Response|string
     */
    public function display($scenarioAlias, $stepName, array $queryParameters = []);

    /**
     * Move forward.
     * If step was completed, redirect to next step, otherwise return response.
     *
     * @param string $scenarioAlias
     * @param string $stepName
     *
     * @return Response|string
     */
    public function forward($scenarioAlias, $stepName);

    /**
     * Register new flow scenario.
     *
     * @param string $alias
     * @param FlowScenarioInterface $scenario
     */
    public function registerScenario($alias, FlowScenarioInterface $scenario);

    /**
     * Load flow scenario with given alias.
     *
     * @param string $scenario
     *
     * @return FlowScenarioInterface
     */
    public function loadScenario($scenario);
}

