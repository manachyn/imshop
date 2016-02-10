<?php

namespace app\modules\tasks\components;

interface TaskHandlerInterface
{
    /**
     * Handle task.
     *
     * @param TaskInterface $task
     * @return mixed
     */
    public function handleTask(TaskInterface $task);
} 