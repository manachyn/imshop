<?php

namespace im\flow\coordinator;

use im\flow\builder\FlowBuilderInterface;
use im\flow\FlowContextInterface;
use im\flow\FlowInterface;
use im\flow\scenario\FlowScenarioInterface;
use im\flow\Step\ActionResult;
use im\flow\step\StepInterface;
use InvalidArgumentException;
use Yii;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class Coordinator
 * @package im\flow
 */
class Coordinator implements CoordinatorInterface
{
    /**
     * Flow builder.
     *
     * @var FlowBuilderInterface
     */
    protected $builder;

    /**
     * Flow context.
     *
     * @var FlowContextInterface
     */
    protected $context;

    /**
     * Registered scenarios.
     *
     * @var array
     */
    protected $scenarios = [];

    /**
     * Coordinator constructor.
     * @param FlowBuilderInterface $builder
     * @param FlowContextInterface $context
     */
    public function __construct(FlowBuilderInterface $builder, FlowContextInterface $context)
    {
        $this->builder = $builder;
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function start($scenarioAlias, array $queryParameters = null)
    {
        $flow = $this->buildFlow($scenarioAlias);
        $step = $flow->getFirstStep();

        $this->context->initialize($flow, $step);
        $this->context->close();

        if (!$this->context->isValid()) {
            return $this->processStepResult($flow, $this->context->getFlow()->getValidator()->getResponse($step));
        }

        return $this->redirectToStepDisplayAction($flow, $step, $queryParameters);
    }

    /**
     * {@inheritdoc}
     */
    public function display($scenarioAlias, $stepName, array $queryParameters = [])
    {
        $flow = $this->buildFlow($scenarioAlias);
        $step = $flow->getStepByName($stepName);

        $this->context->initialize($flow, $step);

        try {
            $this->context->rewindHistory();
        } catch (NotFoundHttpException $e) {
            return $this->goToLastValidStep($flow, $scenarioAlias);
        }

        return $this->processStepResult(
            $flow,
            $this->context->isValid() ? $step->displayAction($this->context) : $this->context->getFlow()->getValidator()->getResponse($step)
        );
    }

    /**
     * {@inheritdoc}
     */
    public function forward($scenarioAlias, $stepName)
    {
        $flow = $this->buildFlow($scenarioAlias);
        $step = $flow->getStepByName($stepName);

        $this->context->initialize($flow, $step);

        try {
            $this->context->rewindHistory();
        } catch (NotFoundHttpException $e) {
            return $this->goToLastValidStep($flow, $scenarioAlias);
        }

        return $this->processStepResult(
            $flow,
            $this->context->isValid() ? $step->forwardAction($this->context) : $this->context->getFlow()->getValidator()->getResponse($step)
        );
    }

    /**
     * @param FlowInterface $flow
     * @param $result
     * @return Response
     */
    public function processStepResult(FlowInterface $flow, $result)
    {
        if ($result instanceof Response || is_string($result)) {
            return $result;
        }

        if ($result instanceof ActionResult) {
            // Handle explicit jump to step.
            if ($result->getNextStepName()) {
                $this->context->setNextStepByName($result->getNextStepName());

                return $this->redirectToStepDisplayAction($flow, $this->context->getNextStep());
            }

            // Handle last step.
            if ($this->context->isLastStep()) {
                $this->context->close();
                $url = Url::toRoute(array_merge([$flow->getRedirect()], $flow->getRedirectParams()));

                return Yii::$app->response->redirect($url, 301);
            }

            // Handle default linear behaviour.
            return $this->redirectToStepDisplayAction($flow, $this->context->getNextStep());
        }

        throw new \RuntimeException('Wrong action result, expected Response, string or ActionResult');
    }

    /**
     * {@inheritdoc}
     */
    public function registerScenario($alias, FlowScenarioInterface $scenario)
    {
        if (isset($this->scenarios[$alias])) {
            throw new InvalidArgumentException(
                sprintf('Flow scenario with alias "%s" is already registered', $alias)
            );
        }

        $this->scenarios[$alias] = $scenario;
    }

    /**
     * {@inheritdoc}
     */
    public function loadScenario($alias)
    {
        if (!isset($this->scenarios[$alias])) {
            throw new InvalidArgumentException(sprintf('Flow scenario with alias "%s" is not registered', $alias));
        }

        return $this->scenarios[$alias];
    }

    /**
     * Redirect to step display action.
     *
     * @param FlowInterface $flow
     * @param StepInterface $step
     * @param array $queryParameters
     *
     * @return Response
     */
    protected function redirectToStepDisplayAction(
        FlowInterface $flow,
        StepInterface $step,
        array $queryParameters = []
    ) {
        $this->context->addStepToHistory($step->getName());

        if (null !== $route = $flow->getDisplayRoute()) {
            $url = Url::toRoute(array_merge(
                [$route],
                $flow->getDisplayRouteParams(),
                ['stepName' => $step->getName()],
                $queryParameters
            ));

            return Yii::$app->response->redirect($url, 301);
        }

        // Default parameters for display route
        $routeParameters = [
            'scenarioAlias' => $flow->getScenarioAlias(),
            'stepName' => $step->getName(),
        ];

        if (null !== $queryParameters) {
            $routeParameters = array_merge($queryParameters, $routeParameters);
        }

        return Yii::$app->response->redirect(Url::toRoute(array_merge(['@flow_display'], $routeParameters)), 301);
    }

    /**
     * @param FlowInterface $flow
     * @param string $scenarioAlias
     *
     * @return Response
     */
    protected function goToLastValidStep(FlowInterface $flow, $scenarioAlias)
    {
        //the step we are supposed to display was not found in the history.
        if (null === $this->context->getPreviousStep()) {
            //there is no previous step go to start
            return $this->start($scenarioAlias);
        }

        //we will go back to previous step...
        $history = $this->context->getStepHistory();
        if (empty($history)) {
            //there is no history
            return $this->start($scenarioAlias);
        }
        $step = $flow->getStepByName(end($history));

        $this->context->initialize($flow, $step);

        return $this->redirectToStepDisplayAction($flow, $step);
    }

    /**
     * Builds flow for given scenario alias.
     *
     * @param string $scenarioAlias
     *
     * @return FlowInterface
     */
    protected function buildFlow($scenarioAlias)
    {
        $flow = $this->builder->build($this->loadScenario($scenarioAlias));
        $flow->setScenarioAlias($scenarioAlias);

        return $flow;
    }
}

