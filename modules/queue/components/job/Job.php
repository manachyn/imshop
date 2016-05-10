<?php

namespace app\modules\queue\components\job;

use app\modules\queue\components\interfaces\EntityResolverInterface;
use app\modules\queue\components\interfaces\QueueableEntityInterface;

abstract class Job implements JobInterface
{
    /**
     * @var mixed the job handler instance
     */
    protected $instance;

    /**
     * @var string the name of the queue the job belongs to.
     */
    protected $queue;

    /**
     * @var bool indicates if the job has been deleted.
     */
    protected $deleted = false;

    /**
     * @var bool indicates if the job has been failed.
     */
    protected $failed = false;

    /**
     * @var bool indicates if the job has been released.
     */
    protected $released = false;

    /**
     * @inheritdoc
     */
    public function delete()
    {
        $this->deleted = true;
    }

    /**
     * @inheritdoc
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * @inheritdoc
     */
    public function failed()
    {
        $this->failed = true;

        $payload = json_decode($this->getDescriptor(), true);

        list($class, $method) = $this->parseJob($payload['job']);

        $this->instance = $this->resolve($class);

        if (method_exists($this->instance, 'failed')) {
            $this->instance->failed($this->resolveEntities($payload['data']));
        }
    }

    /**
     * @inheritdoc
     */
    public function isFailed()
    {
        return $this->failed;
    }

    /**
     * @inheritdoc
     */
    public function release($delay = 0)
    {
        $this->released = true;
    }

    /**
     * @inheritdoc
     */
    public function isReleased()
    {
        return $this->released;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return json_decode($this->getDescriptor(), true)['job'];
    }

    /**
     * @inheritdoc
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * Get the current system time.
     *
     * @return int
     */
    protected function getTime()
    {
        return time();
    }

    /**
     * Resolve and perform the job handler method.
     *
     * @param array $descriptor
     * @return void
     */
    protected function resolveAndPerform(array $descriptor)
    {
        list($class, $method) = $this->parseJob($descriptor['job']);

        $this->instance = $this->resolve($class);

        $this->instance->{$method}($this, $this->resolveEntities($descriptor['data']));
    }

    /**
     * Parse the job declaration into class and method.
     *
     * @param string $job
     * @return array
     */
    protected function parseJob($job)
    {
        $segments = explode('@', $job);

        return count($segments) > 1 ? $segments : [$segments[0], 'perform'];
    }

    /**
     * Resolve the given job handler.
     *
     * @param string $class
     * @return object
     */
    protected function resolve($class)
    {
        return \Yii::createObject($class);
    }

    /**
     * Resolve entities in the given descriptor.
     *
     * @param mixed $data
     * @return mixed
     */
    protected function resolveEntities($data)
    {
        if (is_string($data)) {
            return $this->resolveEntity($data);
        }

        if (is_array($data)) {
            array_walk($data, function (&$d) { $d = $this->resolveEntity($d); });
        }

        return $data;
    }

    /**
     * Resolve entity.
     *
     * @param mixed $value
     * @return QueueableEntityInterface
     */
    protected function resolveEntity($value)
    {
        if (is_string($value) && starts_with($value, '::entity::')) {
            list($marker, $type, $id) = explode('|', $value, 3);

            return $this->getEntityResolver()->resolve($type, $id);
        }

        return $value;
    }

    /**
     * Get an entity resolver instance.
     *
     * @return EntityResolverInterface
     */
    protected function getEntityResolver()
    {
        return \Yii::createObject('app\modules\queue\components\interfaces\EntityResolverInterface');
    }
} 