<?php

namespace app\modules\queue\components\interfaces;

use app\modules\queue\components\job\JobInterface;

interface FailedJobProviderInterface
{
    /**
     * Save failed job.
     *
     * @param string $queue
     * @param string $queueName
     * @param JobInterface $job
     */
    public function save($queue, $queueName, JobInterface $job);
} 