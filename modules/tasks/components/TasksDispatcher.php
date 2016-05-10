<?php

namespace app\modules\tasks\components;

use app\modules\queue\components\interfaces\QueuedInterface;
use app\modules\queue\components\interfaces\QueueInterface;
use app\modules\queue\components\interfaces\QueueResolverInterface;
use app\modules\queue\components\QueueDescriptor;
use app\modules\queue\components\QueueManager;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\base\InvalidParamException;
use yii\di\Instance;

class TasksDispatcher extends Component implements TasksDispatcherInterface
{
    /**
     * @var QueueDescriptor[]
     */
    public $queues = [];

    /**
     * @var QueueResolverInterface
     */
    public $queueResolver;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->queueResolver = Instance::ensure($this->queueResolver, 'app\modules\queue\components\interfaces\QueueResolverInterface');

        foreach ($this->queues as $key => $queue) {
            if (!$queue instanceof QueueDescriptor) {
                $this->queues[$key] = $this->normalizeQueueDescriptor($queue);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function dispatch(TaskInterface $task)
    {
        if ($this->taskShouldBeQueued($task)) {
            return $this->dispatchToQueue($task);
        } else {
            return $this->dispatchNow($task);
        }
    }

    /**
     * @inheritdoc
     */
    public function dispatchNow($task, \Closure $afterResolving = null)
    {
        $handler = $this->resolveHandler($task);
        $method = 'handleTask';

        if ($afterResolving) {
            call_user_func($afterResolving, $handler);
        }

        return call_user_func([$handler, $method], $task);
    }

    /**
     * Dispatch a task to its appropriate handler behind a queue.
     *
     * @param TaskInterface $task
     * @param QueueInterface|null $queue
     * @param string $queueName
     * @throws \RuntimeException
     * @return mixed
     */
    public function dispatchToQueue(TaskInterface $task, QueueInterface $queue = null, $queueName = '')
    {
        if (is_null($queue)) {
            $queueDescriptor = $this->resolveQueue($task);
            if (!is_null($queueDescriptor)) {
                $queue = $this->queueResolver->getQueue($queueDescriptor->name);
                $queueName = $queueDescriptor->name;
            } else {
                $queue = $this->queueResolver->getQueue();
            }
        }

        if (!$queue instanceof QueueInterface) {
            throw new \RuntimeException('Invalid queue implementation.');
        }

        $this->pushtaskToQueue($task, $queue, $queueName);
    }

    /**
     * Get the handler instance for the given task.
     *
     * @param TaskInterface $task
     * @return TaskHandlerInterface
     */
    protected function resolveHandler(TaskInterface $task)
    {
        return $task->getHandler();
    }

    /**
     * Get the handler instance for the given command.
     *
     * @param TaskInterface $task
     * @return QueueDescriptor|null
     */
    protected function resolveQueue(TaskInterface $task)
    {
        $class = get_class($task);
        if (isset($this->queues[$class])) {
            return $this->queues[$class];
        } else {
            return null;
        }
    }

    /**
     * Determine if the given task should be queued.
     *
     * @param TaskInterface $task
     * @return bool
     */
    protected function taskShouldBeQueued(TaskInterface $task)
    {
        return $task instanceof QueuedInterface;
    }

    /**
     * @param mixed $descriptor
     * @throws InvalidConfigException
     * @return QueueDescriptor
     */
    protected function normalizeQueueDescriptor(QueueDescriptor $descriptor)
    {
        if (is_string($descriptor)) {
            return new QueueDescriptor($descriptor);
        } elseif (is_array($descriptor) && isset($descriptor['queue'])) {
            return new QueueDescriptor($descriptor['queue'], isset($descriptor['name']) ? $descriptor['name'] : '');
        } else {
            throw new InvalidConfigException('Invalid queue descriptor');
        }
    }

    /**
     * Push the task onto the given queue instance.
     *
     * @param TaskInterface $task
     * @param QueueInterface $queue
     * @param string|null $queueName
     */
    protected function pushTaskToQueue(TaskInterface $task, QueueInterface $queue, $queueName = '')
    {
        $job = 'app\modules\tasks\components\QueuedTaskHandler@handle';
        $data['task'] = $task;
        if ($queueName) {
            $queue->pushOn($queueName, $job, $data);
        } else {
            $queue->push($job, $data);
        }
    }
}