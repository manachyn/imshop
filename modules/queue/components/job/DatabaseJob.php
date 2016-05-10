<?php

namespace app\modules\queue\components\job;

use app\modules\queue\components\queues\DatabaseQueue;

class DatabaseJob extends Job implements JobInterface
{
    /**
     * The database queue instance.
     *
     * @var DatabaseQueue
     */
    protected $databaseQueue;

    /**
     * The database job descriptor.
     *
     * @var \StdClass
     */
    protected $job;

    /**
     * Create a new job instance.
     *
     * @param DatabaseQueue $databaseQueue
     * @param \StdClass $job
     * @param string $queue
     */
    public function __construct(DatabaseQueue $databaseQueue, $job, $queue)
    {
        $this->job = $job;
        $this->queue = $queue;
        $this->databaseQueue = $databaseQueue;
        $this->job->attempts = $this->job->attempts + 1;
    }

    /**
     * @inheritdoc
     */
    public function perform()
    {
        $this->resolveAndPerform(json_decode($this->job->descriptor, true));
    }

    /**
     * Delete the job from the queue.
     *
     * @return void
     */
    public function delete()
    {
        parent::delete();

        $this->databaseQueue->delete($this->queue, $this->job->id);
    }

    /**
     * Release the job back into the queue.
     *
     * @param  int  $delay
     * @return void
     */
    public function release($delay = 0)
    {
        parent::release($delay);

        $this->delete();

        $this->databaseQueue->release($this->queue, $this->job, $delay);
    }

    /**
     * @inheritdoc
     */
    public function attempts()
    {
        return (int) $this->job->attempts;
    }

    /**
     * Get the job identifier.
     *
     * @return string
     */
    public function getJobId()
    {
        return $this->job->id;
    }

    /**
     * @inheritdoc
     */
    public function getDescriptor()
    {
        return $this->job->descriptor;
    }

    /**
     * Get queue driver instance.
     *
     * @return DatabaseQueue
     */
    public function getDatabaseQueue()
    {
        return $this->databaseQueue;
    }

    /**
     * Get the database job.
     *
     * @return \StdClass
     */
    public function getDatabaseJob()
    {
        return $this->job;
    }
}
