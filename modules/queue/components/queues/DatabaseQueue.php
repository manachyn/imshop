<?php

namespace app\modules\queue\components\queues;

use app\modules\queue\components\job\DatabaseJob;
use app\modules\queue\components\Queue;
use yii\db\Connection;
use yii\db\Expression;
use yii\di\Instance;

class DatabaseQueue extends Queue
{
    /**
     * @var Connection|array|string the DB connection object or the application component ID of the DB connection.
     */
    public $db = 'db';

    /**
     * @var string the database table that holds the jobs.
     */
    public $table = '{{%queue}}';

    /**
     * @var string the name of the default queue.
     */
    public $defaultName = 'default';

    /**
     * The expiration time of a job.
     *
     * @var int|null
     */
    public $expire = 60;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->db = Instance::ensure($this->db, Connection::className());
    }

    /**
     * @inheritdoc
     */
    public function push($job, $data = '', $queue = null)
    {
        return $this->pushToDatabase(0, $queue, $this->createJobDescriptor($job, $data));
    }

    /**
     * @inheritdoc
     */
    public function pushAfterDelay($delay, $job, $data = '', $queue = null)
    {
        return $this->pushToDatabase($delay, $queue, $this->createJobDescriptor($job, $data));
    }

    /**
     * @inheritdoc
     */
    public function pop($queue = null)
    {
        $queue = $this->getQueue($queue);

        if (!is_null($this->expire)) {
            $this->releaseExpiredJobs($queue);
        }

        $transaction = $this->db->beginTransaction();

        if ($job = $this->getNextAvailableJob($queue)) {
            $this->markJobAsReserved($job->id);
            $transaction->commit();
            return new DatabaseJob($this, $job, $queue);
        } else {
            $transaction->commit();
            return null;
        }
    }

    /**
     * Delete job from the queue.
     *
     * @param string $queue
     * @param string $id
     * @return void
     */
    public function delete($queue, $id)
    {
        $this->db->createCommand()->delete($this->table, ['id' => $id])->execute();
    }

    /**
     * Release job back onto the queue.
     *
     * @param string $queue
     * @param \StdClass $job
     * @param int $delay
     * @return void
     */
    public function release($queue, $job, $delay)
    {
        $this->pushToDatabase($delay, $queue, $job->descriptor, $job->attempts);
    }

    /**
     * Push job descriptor to the database with a given delay.
     *
     * @param \DateTime|int $delay
     * @param string|null $queue
     * @param string $jobDescriptor
     * @param int $attempts
     * @return mixed
     */
    protected function pushToDatabase($delay, $queue, $jobDescriptor, $attempts = 0)
    {
        $attributes = $this->buildDatabaseRecord($this->getQueue($queue), $jobDescriptor, $this->getAvailableAt($delay), $attempts);

        $this->db->createCommand()->insert($this->table, $attributes)->execute();

        return $this->db->lastInsertID;
    }

    /**
     * Get the next available job for the queue.
     *
     * @param string|null $queue
     * @return \StdClass|null
     */
    protected function getNextAvailableJob($queue)
    {
        $params = [':queue' => $this->getQueue($queue), ':reserved' => 0, ':available_at' => $this->getTime()];
        $job = $this->db->createCommand("SELECT * FROM {$this->table} WHERE queue=:queue AND reserved=:reserved
            AND available_at <= :available_at ORDER BY id ASC FOR UPDATE", $params)->queryOne();

        return $job ? (object) $job : null;
    }

    /**
     * Mark the given job ID as reserved.
     *
     * @param string $id
     * @return void
     */
    protected function markJobAsReserved($id)
    {
        $this->db->createCommand()->update($this->table, ['reserved' => 1, 'reserved_at' => $this->getTime()], ['id' => $id])->execute();
    }

    /**
     * Release the jobs that have been reserved for too long.
     *
     * @param string $queue
     * @return void
     */
    protected function releaseExpiredJobs($queue)
    {
        $expired = (new \DateTime())->modify('-' . (int) $this->expire . ' second')->getTimestamp();
        $this->db->createCommand()->update($this->table,
            ['reserved' => 0, 'reserved_at' => null, 'attempts' => new Expression('attempts + 1')],
            ['and', ['queue' =>  $this->getQueue($queue)], ['reserved' => 1], ['<=', 'reserved_at', $expired]]
        )->execute();
    }

    /**
     * Create an array to insert for the given job.
     *
     * @param string|null $queue
     * @param string $jobDescriptor
     * @param int $availableAt
     * @param int $attempts
     * @return array
     */
    protected function buildDatabaseRecord($queue, $jobDescriptor, $availableAt, $attempts = 0)
    {
        return [
            'queue' => $queue,
            'descriptor' => $jobDescriptor,
            'attempts' => $attempts,
            'reserved' => 0,
            'reserved_at' => null,
            'available_at' => $availableAt,
            'created_at' => $this->getTime(),
        ];
    }

    /**
     * Get the queue or return the default.
     *
     * @param string|null $queue
     * @return string
     */
    protected function getQueue($queue)
    {
        return $queue ?: $this->defaultName;
    }

    /**
     * Get the "available at" UNIX timestamp.
     *
     * @param \DateTime|int $delay
     * @return int
     */
    protected function getAvailableAt($delay)
    {
        $availableAt = $delay instanceof \DateTime ? $delay : (new \DateTime())->modify((int) $delay.' second');

        return $availableAt->getTimestamp();
    }
}