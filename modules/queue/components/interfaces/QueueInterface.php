<?php

namespace app\modules\queue\components\interfaces;

use app\modules\queue\components\job\JobInterface;

interface QueueInterface
{
    /**
     * Push a new job onto the queue.
     *
     * @param string $job
     * @param mixed $data
     * @param string $queue
     * @return mixed
     */
    public function push($job, $data = '', $queue = null);

    /**
     * Push a new job onto the queue after a delay.
     *
     * @param \DateTime|int $delay
     * @param string $job
     * @param mixed $data
     * @param string $queue
     * @return mixed
     */
    public function pushAfterDelay($delay, $job, $data = '', $queue = null);

    /**
     * Push a new job onto the queue.
     *
     * @param string $queue
     * @param string $job
     * @param mixed $data
     * @return mixed
     */
    public function pushOn($queue, $job, $data = '');

    /**
     * Push a new job onto the queue after a delay.
     *
     * @param string $queue
     * @param \DateTime|int $delay
     * @param string $job
     * @param mixed $data
     * @return mixed
     */
    public function pushOnAfterDelay($queue, $delay, $job, $data = '');

    /**
     * Pop the next job off of the queue.
     *
     * @param string $queue
     * @return JobInterface|null
     */
    public function pop($queue = null);
} 