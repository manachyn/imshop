<?php

namespace app\modules\queue\components\interfaces;

interface QueueResolverInterface
{
    /**
     * Resolve a queue instance by name.
     *
     * @param string $name
     * @return QueueInterface
     */
    public function getQueue($name = null);
} 