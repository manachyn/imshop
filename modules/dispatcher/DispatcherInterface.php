<?php

namespace app\modules\dispatcher;

interface DispatcherInterface
{
    /**
     * Dispatch a command to its appropriate handler.
     *
     * @param mixed $command
     * @return mixed
     */
    public function dispatch($command);

    /**
     * Dispatch a command to its appropriate handler in the current process.
     *
     * @param mixed $command
     * @param \Closure|null $afterResolving
     * @return mixed
     */
    public function dispatchNow($command, \Closure $afterResolving = null);
}