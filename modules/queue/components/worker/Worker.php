<?php

namespace app\modules\queue\components\worker;

use app\modules\queue\components\interfaces\FailedJobProviderInterface;
use app\modules\queue\components\interfaces\QueueInterface;
use app\modules\queue\components\job\JobInterface;
use app\modules\queue\components\QueueManager;

class Worker
{
    /**
     * The failed job provider implementation.
     *
     * @var FailedJobProviderInterface
     */
    protected $failedJobProvider;

    /**
     * @param FailedJobProviderInterface $failedJobProvider
     */
    public function __construct(FailedJobProviderInterface $failedJobProvider = null)
    {
        $this->failedJobProvider = $failedJobProvider;
    }

    /**
     * Listen to the given queue in a loop.
     *
     * @param string $queue
     * @param string $queueName
     * @param int $delay
     * @param int $memory
     * @param int $sleep
     * @param int $maxTries
     * @return array
     */
    public function daemon($queue = null, $queueName = null, $delay = 0, $memory = 128, $sleep = 3, $maxTries = 0)
    {
        while (true) {
            if ($this->daemonShouldRun()) {
                $this->runNextJobForDaemon($queue, $queueName, $delay, $sleep, $maxTries);
            } else {
                $this->sleep($sleep);
            }

            if ($this->memoryExceeded($memory)) {
                $this->stop();
            }
        }
    }

    /**
     * Run the next job for the daemon worker.
     *
     * @param string $queue
     * @param string $queueName
     * @param int $delay
     * @param int $sleep
     * @param int $maxTries
     * @return void
     */
    protected function runNextJobForDaemon($queue, $queueName, $delay, $sleep, $maxTries)
    {
        try {
            $this->pop($queue, $queueName, $delay, $sleep, $maxTries);
        } catch (\Exception $e) {

        }
    }

    /**
     * Determine if the daemon should process on this iteration.
     *
     * @return bool
     */
    protected function daemonShouldRun()
    {
        return true;
    }

    /**
     * Listen to the given queue.
     *
     * @param string $queue
     * @param string $queueName
     * @param int $delay
     * @param int $sleep
     * @param int $maxTries
     * @return array
     */
    public function pop($queue = null, $queueName = null, $delay = 0, $sleep = 3, $maxTries = 0)
    {
        /** @var QueueManager $queueManager */
        $queueManager = \Yii::$app->queueManager;

        $queue = $queue ?: $queueManager->defaultQueue;
        $queueInstance = $queueManager->getQueue($queue);

        $job = $this->getNextJob($queueInstance, $queueName);

        if (!is_null($job)) {
            return $this->process($queue, $job, $maxTries, $delay);
        }

        $this->sleep($sleep);

        return ['job' => null, 'failed' => false];
    }

    /**
     * Get the next job from the queue.
     *
     * @param QueueInterface $queue
     * @param string $queueName
     * @return JobInterface
     */
    protected function getNextJob($queue, $queueName)
    {
        if (is_null($queueName)) {
            return $queue->pop();
        }

        foreach (explode(',', $queueName) as $queueName) {
            if (!is_null($job = $queue->pop($queueName))) {
                return $job;
            }
        }
    }

    /**
     * Process a given job from the queue.
     *
     * @param string $queue
     * @param JobInterface $job
     * @param int $maxTries
     * @param int $delay
     * @return array
     * @throws \Exception
     */
    public function process($queue, JobInterface $job, $maxTries = 0, $delay = 0)
    {
        if ($maxTries > 0 && $job->attempts() > $maxTries) {
            if ($this->failedJobProvider) {
                $this->failedJobProvider->save($queue, $job->getQueue(), $job);
                $job->delete();
                $job->failed();
            }
            //TODO trigger event
            return ['job' => $job, 'failed' => true];
        }

        try {
            $job->perform();
            return ['job' => $job, 'failed' => false];
        } catch (\Exception $e) {
            if (!$job->isDeleted()) {
                $job->release($delay);
            }
            throw $e;
        }
    }

    /**
     * Determine if the memory limit has been exceeded.
     *
     * @param int $memoryLimit
     * @return bool
     */
    public function memoryExceeded($memoryLimit)
    {
        return (memory_get_usage() / 1024 / 1024) >= $memoryLimit;
    }

    /**
     * Stop listening and bail out of the script.
     *
     * @return void
     */
    public function stop()
    {
        die;
    }

    /**
     * Sleep the script for a given number of seconds.
     *
     * @param int $seconds
     * @return void
     */
    public function sleep($seconds)
    {
        sleep($seconds);
    }

    /**
     * Set the failed job provider instance.
     *
     * @param FailedJobProviderInterface $failedJobProvider
     */
    public function setFailedJobProvider(FailedJobProviderInterface $failedJobProvider)
    {
        $this->failedJobProvider = $failedJobProvider;
    }
}
