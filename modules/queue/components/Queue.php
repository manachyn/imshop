<?php

namespace app\modules\queue\components;

use app\modules\queue\components\interfaces\QueueableEntityInterface;
use app\modules\queue\components\interfaces\QueueInterface;
use Closure;
use SuperClosure\Serializer;
use yii\base\Component;

abstract class Queue extends Component implements QueueInterface
{
    /**
     * @inheritdoc
     */
    public function pushOn($queue, $job, $data = '')
    {
        return $this->push($job, $data, $queue);
    }

    /**
     * @inheritdoc
     */
    public function pushOnAfterDelay($queue, $delay, $job, $data = '')
    {
        return $this->pushAfterDelay($delay, $job, $data, $queue);
    }

    /**
     * Creates job descriptor string from the given job and data.
     *
     * @param mixed $job
     * @param mixed $data
     * @param string $queue
     * @return string
     */
    protected function createJobDescriptor($job, $data = '', $queue = null)
    {
        if ($job instanceof Closure) {
            $closure = (new Serializer)->serialize($job);
            return json_encode(['job' => 'app\modules\queue\components\QueuedHandler@handle', 'data' => compact('closure')]);
        } elseif (is_object($job)) {
            return json_encode([
                'job' => 'app\modules\queue\components\QueuedHandler@handle',
                'data' => ['command' => serialize(clone $job)]
            ]);
        }

        return json_encode(['job' => $job, 'data' => $this->prepareEntities($data)]);
    }

    /**
     * Prepare entities for storage in the queue.
     *
     * @param  mixed  $data
     * @return mixed
     */
    protected function prepareEntities($data)
    {
        if ($data instanceof QueueableEntityInterface) {
            return $this->prepareEntity($data);
        }

        if (is_array($data)) {
            array_walk($data, function (&$d) { $d = $this->prepareEntity($d); });
        }

        return $data;
    }

    /**
     * Prepare entity for storage on the queue.
     *
     * @param  mixed  $value
     * @return mixed
     */
    protected function prepareEntity($value)
    {
        if ($value instanceof QueueableEntityInterface) {
            return '::entity::|'.get_class($value).'|'.$value->getQueueableId();
        }

        return $value;
    }

    /**
     * Get the current UNIX timestamp.
     *
     * @return int
     */
    protected function getTime()
    {
        return time();
    }
} 