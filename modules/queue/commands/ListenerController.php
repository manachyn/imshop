<?php

namespace app\modules\queue\commands;

use app\modules\queue\components\QueueManager;
use Symfony\Component\Process\Process;
use yii\console\Controller;

class ListenerController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'listen';

    /**
     * @var string the queue to listen on
     */
    public $queueName;

    /**
     * @var int amount of time to delay failed jobs
     */
    public $delay = 0;

    /**
     * @var int the memory limit in megabytes
     */
    public $memory = 128;

    /**
     * @var int seconds a job may run before timing out
     */
    public $timeout = 60;

    /**
     * @var int seconds to wait before checking queue for jobs.
     */
    public $sleep = 3;

    /**
     * @var int number of times to attempt a job before logging it failed.
     */
    public $tries = 0;

    /**
     * @var string the queue worker command line.
     */
    protected $workerCommand;

    /**
     * The output handler callback.
     *
     * @var \Closure|null
     */
    protected $outputHandler;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->workerCommand = '"'. PHP_BINARY . '" yii queue/worker %s --queueName="%s" --delay=%s --memory=%s --sleep=%s --tries=%s';
        $this->outputHandler = function ($type, $line) {
            $this->stdout($line);
        };
    }

    /**
     * @inheritdoc
     */
    public function options($actionID)
    {
        return array_merge(
            parent::options($actionID),
            ['queueName', 'delay', 'memory', 'timeout', 'sleep', 'tries']
        );
    }

    /**
     * @param string $queue the name of queue
     */
    public function actionListen($queue = null)
    {
        $queueName = $this->queueName ?: $this->getQueueName($queue);
        $process = $this->makeProcess($queue, $queueName, $this->delay, $this->memory, $this->timeout);

        while (true) {
            $this->runProcess($process, $this->memory);
        }
    }

    protected function getQueueName($queue)
    {
        /** @var QueueManager $queueManager */
        $queueManager = \Yii::$app->queueManager;

        if (is_null($queue)) {
            $queue = $queueManager->defaultQueue;
        }

        $queueName = $queueManager->getQueue($queue)->defaultName;

        return $queueName;
    }

    /**
     * Create a new process for the worker.
     *
     * @param string $connection
     * @param string $queue
     * @param int $delay
     * @param int $memory
     * @param int $timeout
     * @return \Symfony\Component\Process\Process
     */
    public function makeProcess($connection, $queue, $delay, $memory, $timeout)
    {
        $string = $this->workerCommand;

        $command = sprintf(
            $string, $connection, $queue, $delay,
            $memory, $this->sleep, $this->tries
        );

        return new Process($command, null, null, null, $timeout);
    }

    /**
     * Run the given process.
     *
     * @param \Symfony\Component\Process\Process $process
     * @param int $memory
     * @return void
     */
    public function runProcess(Process $process, $memory)
    {
        $process->run(function ($type, $line) {
            $this->handleWorkerOutput($type, $line);
        });

        if ($this->memoryExceeded($memory)) {
            die;
        }
    }

    /**
     * Handle output from the worker process.
     *
     * @param int $type
     * @param string $line
     * @return void
     */
    protected function handleWorkerOutput($type, $line)
    {
        if (isset($this->outputHandler)) {
            call_user_func($this->outputHandler, $type, $line);
        }
    }

    /**
     * Determine if the memory limit has been exceeded.
     *
     * @param int $memoryLimit
     * @return bool
     */
    public function memoryExceeded($memoryLimit)
    {
        return (memory_get_usage() / 1024 / 1024) >= $memoryLimit;
    }
} 