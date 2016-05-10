<?php

namespace app\modules\queue\components;

trait QueueableTrait
{
    /**
     * @var string the name of the queue the job should be sent to
     */
    public $queue;

    /**
     * @var int the seconds before the job should be made available.
     */
    public $delay;

    /**
     * Set the desired queue for the job.
     *
     * @param string $queue
     * @return $this
     */
    public function onQueue($queue)
    {
        $this->queue = $queue;

        return $this;
    }

    /**
     * Set the desired delay for the job.
     *
     * @param int $delay
     * @return $this
     */
    public function delay($delay)
    {
        $this->delay = $delay;

        return $this;
    }
}
