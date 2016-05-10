<?php

namespace im\flow\controllers;

use im\flow\coordinator\CoordinatorInterface;
use im\flow\FlowContextInterface;
use InvalidArgumentException;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class FlowController
 * @package im\search\controllers
 */
class FlowController extends Controller
{
    /**
     * @var CoordinatorInterface
     */
    protected $flowCoordinator;

    /**
     * @var FlowContextInterface
     */
    protected $flowContext;

    /**
     * FlowController constructor.
     * @param string $id the ID of this controller.
     * @param \yii\base\Module $module the module that this controller belongs to.
     * @param CoordinatorInterface $flowCoordinator
     * @param FlowContextInterface $flowContext
     * @param array $config name-value pairs that will be used to initialize the object properties.
     */
    public function __construct($id, $module, CoordinatorInterface $flowCoordinator, FlowContextInterface $flowContext, $config = [])
    {
        $this->flowCoordinator = $flowCoordinator;
        $this->flowContext = $flowContext;
        parent::__construct($id, $module, $config);
    }

    /**
     * Build and start process for given scenario.
     * This action usually redirects to first step.
     *
     * @param string $scenarioAlias
     * @return Response
     */
    public function actionStart($scenarioAlias)
    {
        $request = Yii::$app->request;

        return $this->flowCoordinator->start($scenarioAlias, $request->get());
    }

    /**
     * Execute display action of given step.
     *
     * @param string $scenarioAlias
     * @param string $stepName
     * @throws NotFoundHttpException
     * @return Response
     */
    public function actionDisplay($scenarioAlias, $stepName)
    {
        $request = Yii::$app->request;
        $this->flowContext->setRequest(Yii::$app->request);

        try {
            $result = $this->flowCoordinator->display($scenarioAlias, $stepName, $request->get());
            return is_string($result) ? $this->renderContent($result) : $result;
        } catch (InvalidArgumentException $e) {
            throw new NotFoundHttpException('The step you are looking for is not found.', $e);
        }
    }
}