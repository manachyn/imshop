<?php

namespace app\modules\queue\components;

use app\modules\queue\components\job\JobInterface;

class QueueClosure
{
    /**
     * Fire the Closure based queue job.
     *
     * @param  JobInterface  $job
     * @param  array  $data
     * @return void
     */
    public function fire($job, $data)
    {
        /** @var \Closure $closure */
        $closure = unserialize($data['closure']);
        $closure($job);
    }
}
