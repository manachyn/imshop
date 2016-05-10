<?php

namespace app\modules\queue\components\queues;

use app\modules\queue\components\job\JobInterface;
use app\modules\queue\components\job\SyncJob;
use app\modules\queue\components\Queue;

class SyncQueue extends Queue
{
    /**
     * @inheritdoc
     */
    public function push($job, $data = '', $queue = null)
    {
        $queueJob = $this->resolveJob($this->createJobDescriptor($job, $data, $queue));

        try {
            $queueJob->perform();
        } catch (\Exception $e) {
            $this->handleFailedJob($queueJob);

            throw $e;
        }

        return 0;
    }

    /**
     * Push a new job onto the queue after a delay.
     *
     * @param  \DateTime|int  $delay
     * @param  string  $job
     * @param  mixed   $data
     * @param  string  $queue
     * @return mixed
     */
    public function pushAfterDelay($delay, $job, $data = '', $queue = null)
    {
        return $this->push($job, $data, $queue);
    }

    /**
     * Pop the next job off of the queue.
     *
     * @param  string  $queue
     * @return \Illuminate\Contracts\Queue\Job|null
     */
    public function pop($queue = null)
    {
        //
    }

    /**
     * Resolve a Sync job instance.
     *
     * @param string $descriptor
     * @return JobInterface
     */
    protected function resolveJob($descriptor)
    {
        return new SyncJob($descriptor);
    }

    /**
     * Handle the failed job.
     *
     * @param  \Illuminate\Contracts\Queue\Job  $job
     * @return array
     */
    protected function handleFailedJob(Job $job)
    {
        $job->failed();

        $this->raiseFailedJobEvent($job);
    }

    /**
     * Raise the failed queue job event.
     *
     * @param  \Illuminate\Contracts\Queue\Job  $job
     * @return void
     */
    protected function raiseFailedJobEvent(Job $job)
    {
        $data = json_decode($job->getRawBody(), true);

        if ($this->container->bound('events')) {
            $this->container['events']->fire('illuminate.queue.failed', ['sync', $job, $data]);
        }
    }
}
