<?php

namespace im\flow;

use im\flow\step\StepInterface;
use im\flow\storage\StorageInterface;
use yii\web\NotFoundHttpException;
use yii\web\Request;

/**
 * Class FlowContext
 * @package im\flow
 */
class FlowContext implements FlowContextInterface
{
    /**
     * Flow.
     *
     * @var FlowInterface
     */
    protected $flow;

    /**
     * Current step.
     *
     * @var StepInterface
     */
    protected $currentStep;

    /**
     * Previous step.
     *
     * @var StepInterface
     */
    protected $previousStep;

    /**
     * Next step.
     *
     * @var StepInterface
     */
    protected $nextStep;

    /**
     * Storage.
     *
     * @var StorageInterface
     */
    protected $storage;

    /**
     * Request.
     *
     * @var Request
     */
    protected $request;

    /**
     * Progress in percents.
     *
     * @var int
     */
    protected $progress = 0;

    /**
     * Was the context initialized?
     *
     * @var bool
     */
    protected $initialized = false;

    /**
     * Constructor.
     *
     * @param StorageInterface $storage
     */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(FlowInterface $flow, StepInterface $currentStep)
    {
        $this->flow = $flow;
        $this->currentStep = $currentStep;

        $this->storage->initialize(md5($flow->getScenarioAlias()));

        $steps = $flow->getOrderedSteps();

        foreach ($steps as $index => $step) {
            if ($step === $currentStep) {
                $this->previousStep = isset($steps[$index - 1]) ? $steps[$index - 1] : null;
                $this->nextStep = isset($steps[$index + 1]) ? $steps[$index + 1] : null;

                $this->calculateProgress($index);
            }
        }

        $this->initialized = true;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isValid()
    {
        $this->assertInitialized();

        $validator = $this->flow->getValidator();

        if (null !== $validator) {
            return $validator->isValid($this);
        }

        $history = $this->getStepHistory();

        return 0 === count($history) || in_array($this->currentStep->getName(), $history);
    }

    /**
     * {@inheritdoc}
     */
    public function getFlow()
    {
        $this->assertInitialized();

        return $this->flow;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentStep()
    {
        $this->assertInitialized();

        return $this->currentStep;
    }

    /**
     * {@inheritdoc}
     */
    public function getPreviousStep()
    {
        $this->assertInitialized();

        return $this->previousStep;
    }

    /**
     * {@inheritdoc}
     */
    public function getNextStep()
    {
        $this->assertInitialized();

        return $this->nextStep;
    }

    /**
     * {@inheritdoc}
     */
    public function isFirstStep()
    {
        $this->assertInitialized();

        return null === $this->previousStep;
    }

    /**
     * {@inheritdoc}
     */
    public function isLastStep()
    {
        $this->assertInitialized();

        return null === $this->nextStep;
    }

    /**
     * {@inheritdoc}
     */
    public function close()
    {
        $this->assertInitialized();

        $this->storage->clear();
    }

    /**
     * {@inheritdoc}
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * {@inheritdoc}
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * {@inheritdoc}
     */
    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function getProgress()
    {
        $this->assertInitialized();

        return $this->progress;
    }

    /**
     * {@inheritdoc}
     */
    public function getStepHistory()
    {
        return $this->storage->get('history', []);
    }

    /**
     * {@inheritdoc}
     */
    public function setStepHistory(array $history)
    {
        $this->storage->set('history', $history);
    }

    /**
     * {@inheritdoc}
     */
    public function addStepToHistory($stepName)
    {
        $history = $this->getStepHistory();
        array_push($history, $stepName);
        $this->setStepHistory($history);
    }

    /**
     * {@inheritdoc}
     */
    public function rewindHistory()
    {
        $history = $this->getStepHistory();

        while ($top = end($history)) {
            if ($top !== $this->currentStep->getName()) {
                array_pop($history);
            } else {
                break;
            }
        }

        if (0 === count($history)) {
            throw new NotFoundHttpException(sprintf('Step "%s" not found in step history.', $this->currentStep->getName()));
        }

        $this->setStepHistory($history);
    }

    /**
     * {@inheritdoc}
     */
    public function setNextStepByName($stepName)
    {
        $this->nextStep = $this->flow->getStepByName($stepName);
    }

    /**
     * If context was not initialized, throw exception.
     *
     * @throws \RuntimeException
     */
    protected function assertInitialized()
    {
        if (!$this->initialized) {
            throw new \RuntimeException('Flow context was not initialized');
        }
    }

    /**
     * Calculates progress based on current step index.
     *
     * @param int $currentStepIndex
     */
    protected function calculateProgress($currentStepIndex)
    {
        $this->progress = floor(($currentStepIndex + 1) / $this->flow->countSteps() * 100);
    }
}
