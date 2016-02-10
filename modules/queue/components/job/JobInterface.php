<?php

namespace app\modules\queue\components\job;

interface JobInterface
{
    /**
     * Perform the job.
     *
     * @return void
     */
    public function perform();

    /**
     * Delete the job from the queue.
     *
     * @return void
     */
    public function delete();

    /**
     * Determine if the job has been deleted.
     *
     * @return bool
     */
    public function isDeleted();

    /**
     * Call for failed job.
     *
     * @return void
     */
    public function failed();

    /**
     * Release the job back into the queue.
     *
     * @param  int   $delay
     * @return void
     */
    public function release($delay = 0);

    /**
     * Determine if the job has been released.
     *
     * @return bool
     */
    public function isReleased();

    /**
     * Get the number of times the job has been attempted.
     *
     * @return int
     */
    public function attempts();

    /**
     * Get the name of the queued job class.
     *
     * @return string
     */
    public function getName();

    /**
     * Get the name of the queue the job belongs to.
     *
     * @return string
     */
    public function getQueue();

    /**
     * Get descriptor string for the job.
     *
     * @return string
     */
    public function getDescriptor();
} 