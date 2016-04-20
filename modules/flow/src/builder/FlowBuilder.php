<?php

namespace im\flow\builder;

use im\base\di\ContainerAwareInterface;
use im\base\di\ServiceLocatorAwareInterface;
use im\flow\Flow;
use im\flow\FlowInterface;
use im\flow\FlowValidatorInterface;
use im\flow\scenario\FlowScenarioInterface;
use im\flow\step\StepInterface;
use Yii;
use yii\base\Application;
use yii\di\Container;

class FlowBuilder implements FlowBuilderInterface
{
    /**
     * Container.
     *
     * @var Container
     */
    protected $container;

    /**
     * @var Application
     */
    protected $app;

    /**
     * Registered steps.
     *
     * @var StepInterface[]
     */
    protected $steps;

    /**
     * Current process.
     *
     * @var FlowInterface
     */
    protected $process;

    /**
     * Constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->app = Yii::$app;
    }

    /**
     * {@inheritdoc}
     */
    public function build(FlowScenarioInterface $scenario)
    {
        $this->process = new Flow();

        $scenario->build($this);

        return $this->process;
    }

    /**
     * {@inheritdoc}
     */
    public function add($name, $step)
    {
        $this->assertHasProcess();

        if (is_string($step)) {
            $step = $this->loadStep($step);
        }

        if (!$step instanceof StepInterface) {
            throw new \InvalidArgumentException('Step added via builder must implement "Sylius\Bundle\FlowBundle\Process\Step\StepInterface"');
        }

        if ($step instanceof ContainerAwareInterface) {
            $step->setContainer($this->container);
        }

        if ($step instanceof ServiceLocatorAwareInterface) {
            $step->setServiceLocator($this->app);
        }

        $step->setName($name);

        $this->process->addStep($name, $step);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function remove($name)
    {
        $this->assertHasProcess();

        $this->process->removeStep($name);
    }

    /**
     * {@inheritdoc}
     */
    public function has($name)
    {
        $this->assertHasProcess();

        return $this->process->hasStep($name);
    }

    /**
     * {@inheritdoc}
     */
    public function setDisplayRoute($route)
    {
        $this->assertHasProcess();

        $this->process->setDisplayRoute($route);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setDisplayRouteParams(array $params)
    {
        $this->assertHasProcess();

        $this->process->setDisplayRouteParams($params);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setForwardRoute($route)
    {
        $this->assertHasProcess();

        $this->process->setForwardRoute($route);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setForwardRouteParams(array $params)
    {
        $this->assertHasProcess();

        $this->process->setForwardRouteParams($params);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setRedirect($redirect)
    {
        $this->assertHasProcess();

        $this->process->setRedirect($redirect);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setRedirectParams(array $params)
    {
        $this->assertHasProcess();

        $this->process->setRedirectParams($params);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($validator)
    {
        $this->assertHasProcess();

        if ($validator instanceof \Closure) {
            $validator = $this->container->get('sylius.process.validator')->setValidation($validator);
        }

        if (!$validator instanceof FlowValidatorInterface) {
            throw new \InvalidArgumentException();
        }

        $this->process->setValidator($validator);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function registerStep($alias, StepInterface $step)
    {
        if (isset($this->steps[$alias])) {
            throw new \InvalidArgumentException(sprintf('Flow step with alias "%s" is already registered', $alias));
        }

        $this->steps[$alias] = $step;
    }

    /**
     * {@inheritdoc}
     */
    public function loadStep($alias)
    {
        if (!isset($this->steps[$alias])) {
            throw new \InvalidArgumentException(sprintf('Flow step with alias "%s" is not registered', $alias));
        }

        return $this->steps[$alias];
    }

    /**
     * If process do not exists, throw exception.
     *
     * @throws \RuntimeException
     */
    protected function assertHasProcess()
    {
        if (!$this->process) {
            throw new \RuntimeException('Process is not set');
        }
    }
}
