<?php

namespace app\modules\queue\commands;

use app\modules\queue\components\job\JobInterface;
use app\modules\queue\components\worker\Worker;
use yii\console\Controller;
use yii\helpers\Console;

class WorkerController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'work';

    /**
     * @var string the queue to listen on
     */
    public $queueName;

    /**
     * @var bool run the worker in daemon mode
     */
    public $daemon;

    /**
     * @var int amount of time to delay failed jobs
     */
    public $delay = 0;

    /**
     * @var bool force the worker to run even in maintenance mode
     */
    public $force;

    /**
     * @var int the memory limit in megabytes
     */
    public $memory = 128;

    /**
     * @var int number of seconds to sleep when no job is available.
     */
    public $sleep = 3;

    /**
     * @var int number of times to attempt a job before logging it failed.
     */
    public $tries = 0;

    /**
     * @var Worker queue worker instance
     */
    protected $worker;


    public function init()
    {
        $this->worker = \Yii::createObject('app\modules\queue\components\worker\Worker');
    }

    /**
     * @inheritdoc
     */
    public function options($actionID)
    {
        return array_merge(
            parent::options($actionID),
            ['queueName', 'daemon', 'delay', 'force', 'memory', 'sleep', 'tries']
        );
    }

    /**
     * @param string $queue the name of queue
     */
    public function actionWork($queue = null)
    {
        $response = $this->runWorker($queue, $this->queueName, $this->delay, $this->memory, $this->daemon);

        if (!is_null($response['job'])) {
            $this->writeOutput($response['job'], $response['failed']);
        }
    }

    /**
     * Run the worker instance.
     *
     * @param string $queue
     * @param string $queueName
     * @param int $delay
     * @param int $memory
     * @param bool $daemon
     * @return array
     */
    protected function runWorker($queue, $queueName, $delay, $memory, $daemon = false)
    {
        if ($daemon) {
            return $this->worker->daemon($queue, $queueName, $delay, $memory, $this->sleep, $this->tries);
        }

        return $this->worker->pop($queue, $queueName, $delay, $this->sleep, $this->tries);
    }

    /**
     * Write the status output for the queue worker.
     *
     * @param JobInterface $job
     * @param bool $failed
     * @return void
     */
    protected function writeOutput(JobInterface $job, $failed)
    {
        if ($failed) {
            $this->stdout('Failed: ' . $job->getName(), Console::FG_RED);
        } else {
            $this->stdout('Processed: ' . $job->getName(), Console::BG_GREEN);
        }
    }
} 