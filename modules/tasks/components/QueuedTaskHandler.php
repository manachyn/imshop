<?php

namespace app\modules\tasks\components;

use app\modules\queue\components\InteractsWithQueueTrait;
use app\modules\queue\components\job\JobInterface;

class QueuedTaskHandler
{
    /**
     * The tasks dispatcher implementation.
     *
     * @var TasksDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @param TasksDispatcherInterface $dispatcher
     */
    public function __construct(TasksDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Handle the queued task job.
     *
     * @param \app\modules\queue\components\job\JobInterface $job
     * @param array $data
     * @return void
     */
    public function handle(JobInterface $job, $data)
    {
        /** @var TaskInterface $task */
        $task = $data['task'];

        $task->setJob($job);

        $this->dispatcher->dispatchNow($task);
    }
} 