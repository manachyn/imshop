<?php

namespace app\modules\queue\components;

use app\modules\queue\components\job\JobInterface;

trait InteractsWithQueueTrait
{
    /**
     * The underlying queue job instance.
     *
     * @var JobInterface
     */
    protected $job;

    /**
     * Delete the job from the queue.
     *
     * @return void
     */
    public function delete()
    {
        if ($this->job) {
            $this->job->delete();
        }
    }

    /**
     * Release the job back into the queue.
     *
     * @param int $delay
     * @return void
     */
    public function release($delay = 0)
    {
        if ($this->job) {
            $this->job->release($delay);
        }
    }

    /**
     * Get the number of times the job has been attempted.
     *
     * @return int
     */
    public function attempts()
    {
        return $this->job ? $this->job->attempts() : 1;
    }

    /**
     * Set the base queue job instance.
     *
     * @param JobInterface $job
     * @return $this
     */
    public function setJob(JobInterface $job)
    {
        $this->job = $job;

        return $this;
    }
} 