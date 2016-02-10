<?php

namespace app\modules\tasks\components;

interface TasksDispatcherInterface
{
    /**
     * Dispatch a task to its appropriate handler.
     *
     * @param TaskInterface $task
     * @return mixed
     */
    public function dispatch(TaskInterface $task);

    /**
     * Dispatch a task to its appropriate handler in the current process.
     *
     * @param mixed $task
     * @param \Closure|null $afterResolving
     * @return mixed
     */
    public function dispatchNow($task, \Closure $afterResolving = null);
} 