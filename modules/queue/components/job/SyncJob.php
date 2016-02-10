<?php

namespace app\modules\queue\components\job;

class SyncJob extends Job
{
    /**
     * The queue message data.
     *
     * @var string
     */
    protected $descriptor;

    /**
     * Create a new job instance.
     *
     * @param string $descriptor
     */
    public function __construct($descriptor)
    {
        $this->descriptor = $descriptor;
    }

    /**
     * Perform the job.
     *
     * @return void
     */
    public function perform()
    {
        // TODO: Implement perform() method.
    }

    /**
     * Get the number of times the job has been attempted.
     *
     * @return int
     */
    public function attempts()
    {
        // TODO: Implement attempts() method.
    }

    /**
     * Get descriptor string for the job.
     *
     * @return string
     */
    public function getDescriptor()
    {
        // TODO: Implement getDescriptor() method.
    }
}