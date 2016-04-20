<?php

namespace im\flow;

use im\flow\step\StepInterface;
use im\flow\storage\StorageInterface;
use yii\web\NotFoundHttpException;
use yii\web\Request;

/**
 * Interface FlowContextInterface
 * @package im\flow
 */
interface FlowContextInterface
{
    /**
     * Initialize context with flow and current step.
     *
     * @param FlowInterface $flow
     * @param StepInterface $currentStep
     * @return FlowInterface
     */
    public function initialize(FlowInterface $flow, StepInterface $currentStep);

    /**
     * Get flow.
     *
     * @return FlowInterface
     */
    public function getFlow();

    /**
     * Get current step.
     *
     * @return StepInterface
     */
    public function getCurrentStep();

    /**
     * Get previous step.
     *
     * @return StepInterface
     */
    public function getPreviousStep();

    /**
     * Get next step.
     *
     * @return StepInterface
     */
    public function getNextStep();

    /**
     * Is current step the first step?
     *
     * @return bool
     */
    public function isFirstStep();

    /**
     * Is current step the last step?
     *
     * @return bool
     */
    public function isLastStep();

    /**
     * Override the default next step.
     *
     * @param string $stepAlias
     */
    public function setNextStepByName($stepAlias);

    /**
     * Close context and clear all the data.
     */
    public function close();

    /**
     * Is current flow valid?
     *
     * @return bool
     */
    public function isValid();

    /**
     * Get storage.
     *
     * @return StorageInterface
     */
    public function getStorage();

    /**
     * Set storage.
     *
     * @param StorageInterface $storage
     */
    public function setStorage(StorageInterface $storage);

    /**
     * Get current request.
     *
     * @return Request
     */
    public function getRequest();

    /**
     * Set current request.
     *
     * @param Request $request
     */
    public function setRequest(Request $request);

    /**
     * Get progress in percents.
     *
     * @return int
     */
    public function getProgress();

    /**
     * The array contains the history of all the step names.
     *
     * @return array
     */
    public function getStepHistory();

    /**
     * Set a new history of step names.
     *
     * @param array $history
     */
    public function setStepHistory(array $history);

    /**
     * Add the given name to the history of step names.
     *
     * @param string $stepName
     */
    public function addStepToHistory($stepName);

    /**
     * Goes back from the end fo the history and deletes all step names until the current one is found.
     *
     * @throws NotFoundHttpException If the step name is not found in the history.
     */
    public function rewindHistory();
}
