<?php

namespace app\modules\queue\components\dispatcher;

use app\modules\dispatcher\DispatcherInterface;
use app\modules\queue\components\interfaces\QueuedInterface;
use app\modules\queue\components\interfaces\QueueInterface;
use app\modules\queue\components\interfaces\QueueResolverInterface;
use app\modules\queue\components\job\SelfHandlingInterface;
use Closure;
use ReflectionFunction;
use ReflectionMethod;
use yii\base\InvalidParamException;
use yii\base\Object;
use Yii;

class Dispatcher extends Object implements DispatcherInterface, QueueingDispatcherInterface
{
    /**
     * @var QueueResolverInterface
     */
    protected $queueResolver;

    /**
     * @var array command to handler map.
     */
    protected $handlers = [];

    /**
     * @param QueueResolverInterface $queueResolver
     * @param array $config
     */
    public function __construct(QueueResolverInterface $queueResolver = null, $config = [])
    {
        $this->queueResolver = $queueResolver;
        parent::__construct($config);
    }

    /**
     * @param QueueResolverInterface $queueResolver
     */
    public function setQueueResolver(QueueResolverInterface $queueResolver)
    {
        $this->queueResolver = $queueResolver;
    }

    /**
     * @inheritdoc
     */
    public function dispatch($command)
    {
        if ($this->queueResolver && $this->commandShouldBeQueued($command)) {
            return $this->dispatchToQueue($command);
        } else {
            return $this->dispatchNow($command);
        }
    }

    /**
     * @inheritdoc
     */
    public function dispatchNow($command, Closure $afterResolving = null)
    {
        if ($command instanceof SelfHandlingInterface && method_exists($command, 'handle')) {
            $callback = [$command, 'handle'];
            $dependencies = $this->getMethodDependencies($callback);
            return call_user_func_array($callback, $dependencies);
        }

        $handler = $this->resolveHandler($command);
        $method = $this->resolveHandlerMethod($command);

        if ($afterResolving) {
            call_user_func($afterResolving, $handler);
        }

        return call_user_func([$handler, $method], $command);
    }

    /**
     * @inheritdoc
     */
    public function dispatchToQueue($command)
    {
        $queue = $this->queueResolver->getQueue();

        if (!$queue instanceof QueueInterface) {
            throw new \RuntimeException('Queue resolver did not return a Queue implementation.');
        }

        $this->pushCommandToQueue($queue, $command);
    }

    /**
     * Get the handler instance for the given command.
     *
     * @param mixed $command
     * @throws InvalidParamException
     * @return mixed
     */
    public function resolveHandler($command)
    {
        if ($command instanceof SelfHandlingInterface) {
            return $command;
        }

        $className = get_class($command);

        if (isset($this->handlers[$className])) {
            return Yii::createObject(explode('@', $this->handlers[$className])[0]);
        }

        throw new InvalidParamException("No handler registered for command $className");
    }

    /**
     * Get the handler method for the given command.
     *
     * @param mixed $command
     * @throws InvalidParamException
     * @return string
     */
    public function resolveHandlerMethod($command)
    {
        if ($command instanceof SelfHandlingInterface) {
            return 'handle';
        }

        $className = get_class($command);

        if (isset($this->handlers[$className])) {
            $parts = explode('@', $this->handlers[$className]);
            return isset($parts[1]) ? $parts[1] : 'handle';
        }

        throw new InvalidParamException("No handler registered for command $className");
    }

    /**
     * Determine if the given command should be queued.
     *
     * @param mixed $command
     * @return bool
     */
    protected function commandShouldBeQueued($command)
    {
        return $command instanceof QueuedInterface;
    }

    /**
     * Push the command onto the given queue instance.
     *
     * @param QueueInterface $queue
     * @param mixed $command
     * @return void
     */
    protected function pushCommandToQueue($queue, $command)
    {
        if (isset($command->queue) && isset($command->delay)) {
            $queue->pushOnAfterDelay($command->queue, $command->delay, $command);
        } elseif (isset($command->queue)) {
            $queue->pushOn($command->queue, $command);
        } elseif (isset($command->delay)) {
            $queue->pushAfterDelay($command->delay, $command);
        } else {
            $queue->push($command);
        }
    }

    /**
     * Get all dependencies for a given method.
     *
     * @param callable|string $callback
     * @param array $parameters
     * @return array
     */
    protected function getMethodDependencies($callback, array $parameters = [])
    {
        $dependencies = [];

        foreach ($this->getCallReflector($callback)->getParameters() as $parameter) {
            if (array_key_exists($parameter->name, $parameters)) {
                $dependencies[] = $parameters[$parameter->name];
                unset($parameters[$parameter->name]);
            } elseif ($parameter->getClass()) {
                $dependencies[] = Yii::$container->get($parameter->getClass()->name);
            } elseif ($parameter->isDefaultValueAvailable()) {
                $dependencies[] = $parameter->getDefaultValue();
            }
        }

        return array_merge($dependencies, $parameters);
    }

    /**
     * Get the proper reflection instance for the given callback.
     *
     * @param callable|string $callback
     * @return \ReflectionFunctionAbstract
     */
    protected function getCallReflector($callback)
    {
        if (is_string($callback) && strpos($callback, '::') !== false) {
            $callback = explode('::', $callback);
        }

        if (is_array($callback)) {
            return new ReflectionMethod($callback[0], $callback[1]);
        }

        return new ReflectionFunction($callback);
    }
}