<?php

namespace app\modules\queue\components;

use app\modules\queue\components\dispatcher\Dispatcher;
use app\modules\queue\components\job\JobInterface;

class QueuedHandler
{
    /**
     * The dispatcher implementation.
     *
     * @var Dispatcher
     */
    protected $dispatcher;

    /**
     * @param Dispatcher $dispatcher
     */
    public function __construct(Dispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * Handle the queued job.
     *
     * @param \app\modules\queue\components\job\JobInterface $job
     * @param array $data
     * @return void
     */
    public function handle(JobInterface $job, array $data)
    {
        $command = unserialize($data['command']);
        $this->setJobInstance($job, $command);

        $this->dispatcher->dispatchNow($command, function ($handler) use ($job) {
            $this->setJobInstance($job, $handler);
        });

        if (!($job->isDeleted() || $job->isReleased())) {
            $job->delete();
        }
    }

    /**
     * Call the failed method on the job instance.
     *
     * @param  array  $data
     * @return void
     */
    public function failed(array $data)
    {
        $handler = $this->dispatcher->resolveHandler($command = unserialize($data['command']));

        if (method_exists($handler, 'failed')) {
            call_user_func([$handler, 'failed'], $command);
        }
    }

    /**
     * Set the job instance of the given class.
     *
     * @param JobInterface $job
     * @param mixed $instance
     * @return mixed
     */
    protected function setJobInstance(JobInterface $job, $instance)
    {
        if (in_array('app\modules\queue\components\InteractsWithQueueTrait', class_uses_recursive(get_class($instance)))) {
            $instance->setJob($job);
        }

        return $instance;
    }
} 