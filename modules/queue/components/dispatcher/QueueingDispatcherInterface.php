<?php

namespace app\modules\queue\components\dispatcher;

interface QueueingDispatcherInterface
{
    /**
     * Dispatch a command to its appropriate handler behind a queue.
     *
     * @param mixed $command
     * @return mixed
     */
    public function dispatchToQueue($command);
} 