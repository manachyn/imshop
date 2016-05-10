<?php

namespace app\modules\queue\components\dispatcher;

trait DispatchesJobsTrait
{
    /**
     * Dispatch a job to its appropriate handler.
     *
     * @param mixed $job
     * @return mixed
     */
    protected function dispatch($job)
    {
        return \Yii::createObject('app\modules\queue\components\dispatcher\Dispatcher')->dispatch($job);
    }
} 