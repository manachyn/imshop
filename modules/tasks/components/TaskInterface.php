<?php

namespace app\modules\tasks\components;

use app\modules\queue\components\job\JobInterface;

interface TaskInterface
{
    const STATUS_NEW = 0;
    const STATUS_QUEUED = 1;
    const STATUS_IN_PROGRESS = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_FAILED = 4;

    /**
     * Task string presentation.
     *
     * @return string
     */
    public function getName();

    /**
     * Return handler for task instance.
     *
     * @return TaskHandlerInterface
     */
    public function getHandler();

    /**
     * Set the queue job instance.
     *
     * @param JobInterface $job
     */
    public function setJob(JobInterface $job);

    /**
     * Return the queue job instance.
     *
     * @return JobInterface
     */
    public function getJob();
} 