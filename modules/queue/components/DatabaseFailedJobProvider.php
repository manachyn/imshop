<?php

namespace app\modules\queue\components;

use app\modules\queue\components\interfaces\FailedJobProviderInterface;
use app\modules\queue\components\job\JobInterface;
use yii\base\Component;
use yii\db\Connection;
use yii\di\Instance;

class DatabaseFailedJobProvider extends Component implements FailedJobProviderInterface
{
    /**
     * @var Connection|array|string the DB connection object or the application component ID of the DB connection.
     */
    public $db = 'db';

    /**
     * @var string the database table that holds the jobs.
     */
    public $table = '{{%failed_jobs}}';

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
    public function save($queue, $queueName, JobInterface $job)
    {
        $this->db->createCommand()->insert($this->table, [
            'queue' => $queue,
            'queue_name' => $queueName,
            'descriptor' => $job->getDescriptor(),
            'failed_at' => time()
        ])->execute();
    }
}