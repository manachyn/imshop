<?php

namespace app\modules\queue\components;

use app\modules\queue\components\interfaces\QueueResolverInterface;
use yii\base\Component;
use yii\base\InvalidParamException;
use yii\di\Instance;

class QueueManager extends Component implements QueueResolverInterface
{
    /**
     * @var array
     */
    public $queues = [];

    /**
     * @var string
     */
    public $defaultQueue;

    /**
     * @param string $name
     * @throws InvalidParamException
     * @return \app\modules\queue\components\interfaces\QueueInterface
     */
    public function getQueue($name = null)
    {
        $name = $name ?: $this->defaultQueue;
        foreach ($this->queues as $key => $queue) {
            if ($key == $name) {
                return $this->queues[$key] = Instance::ensure($queue, 'app\modules\queue\components\interfaces\QueueInterface');
            }
        }

        throw new InvalidParamException("Queue not found: $name");
    }
} 