<?php

namespace app\modules\queue\components\interfaces;

interface QueueableEntityInterface
{
    /**
     * Get the queueable identity for the entity.
     *
     * @return mixed
     */
    public function getQueueableId();
} 