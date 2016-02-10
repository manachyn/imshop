<?php

namespace app\modules\queue\components;

class QueueDescriptor
{
    /**
     * @var string queue ID
     */
    public $queue;

    /**
     * @var string queue name
     */
    public $name;

    /**
     * @param string $queue
     * @param string $name
     */
    public function __construct($queue, $name = '')
    {
        $this->queue = $queue;
        $this->name = $name;
    }
} 