<?php

namespace im\thruway\commands;

use im\thruway\process\Command;
use im\thruway\process\ProcessManager;
use im\thruway\Thruway;
use React\EventLoop\Factory;
use Thruway\ClientSession;
use Thruway\Connection;
use Thruway\Transport\PawlTransportProvider;
use yii\console\Controller;
use Yii;

class ThruwayController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'start';

    /**
     * @var Thruway
     */
    private $thruway;

    /**
     * @var ProcessManager
     */
    private $processManager;

    /**
     * Start process manager and workers.
     *
     * @param string $worker name
     */
    public function actionStart($worker = '')
    {
        if ($worker) {
            $this->startWorker($worker);
        } else {
            $this->startManager();
        }
    }

    /**
     * Stop worker.
     *
     * @param string $worker name
     */
    public function actionStop($worker)
    {
        $this->call('stop_process', [$worker]);
    }

    /**
     * Restart worker.
     *
     * @param string $worker name
     */
    public function actionRestart($worker)
    {
        $this->call('restart_process', [$worker]);
    }

    /**
     * Get the process status
     */
    public function actionStatus()
    {
        $statuses = $this->call('status');

        if (!$statuses) {
            return;
        }

        foreach ($statuses as $status) {

            $uptime = 'Not Started';
            if (isset($status->started_at) && $status->status === 'RUNNING') {
                $uptime = 'up since ' . date('l F jS \@ g:i:s a', $status->started_at);
            }

            $pid = null;
            if (isset($status->pid) && $status->status === 'RUNNING') {
                $pid = "pid {$status->pid}";
            }

            $this->stdout(sprintf("%-25s %-3s %-10s %s, %s ", $status->name, $status->process_number, $status->status, $pid, $uptime) . PHP_EOL);
        }
    }

    /**
     * Add a new worker instance to the process.
     *
     * @param string $worker
     */
    public function actionAdd($worker)
    {
        $this->call('add_instance', [$worker]);
    }

    /**
     * Start process manager
     */
    protected function startManager()
    {
        /** @var Thruway $thruway */
        $thruway = $this->thruway = Yii::$app->get('thruway');
        $loop = Factory::create();

        $this->processManager = new ProcessManager('process_manager', $loop, $thruway);
        $this->processManager->addTransportProvider(new PawlTransportProvider($thruway->trustedUrl));

        Yii::info('Starting Thruway Workers...', 'thruway');

        $this->addCmdWorkers();
        $this->addWorkers();

        $this->processManager->start();
    }

    /**
     * Start worker.
     *
     * @param string $worker
     */
    protected function startWorker($worker)
    {
        $this->call('start_process', [$worker]);
    }

    /**
     * Add command workers.
     *
     * These are workers that will only ever have one instance running.
     */
    protected function addCmdWorkers()
    {
        $phpBinary = PHP_BINARY;

        $defaultWorkers = [
            'router' => 'thruway-router/start'
        ];

        //$workers = array_merge($defaultWorkers, $this->thruway->workers);
        $workers = $defaultWorkers;

        foreach ($workers as $name => $command) {
            Yii::info("Adding cmd worker: {$name}");
            $baseDir = Yii::getAlias('@app');
            $cmd = "{$phpBinary} {$baseDir}/yii {$command}";
            $command = new Command($name, $cmd);
            $this->processManager->addCommand($command);
        }
    }

    /**
     * Add regular workers.
     *
     * Theses are workers that can have multiple instances running.
     */
    protected function addWorkers()
    {
        $phpBinary = PHP_BINARY;
        $baseDir = Yii::getAlias('@app');

        foreach ($this->thruway->workers as $name => $worker) {

            Yii::info("Adding worker: {$name}");

            $maxInstances = isset($worker['maxInstances']) ? $worker['maxInstances'] : 1;
            $cmd = "{$phpBinary} {$baseDir}/yii thruway-worker/start {$name} 0";
            $command = new Command($name, $cmd);

            $command->setMaxInstances($maxInstances);
            $this->processManager->addCommand($command);
        }
    }

    /**
     * Make WAMP call
     *
     * @param $uri
     * @param array $args
     * @return null
     */
    private function call($uri, $args = [])
    {
        /** @var Thruway $thruway */
        $thruway = $this->thruway = Yii::$app->get('thruway');
        $result = null;
        $realm  = 'process_manager';

        $connection = new Connection(['realm' => $realm, 'url' => $thruway->trustedUrl, 'max_retries' => 0]);
        $connection->on('open', function (ClientSession $session) use ($uri, $args, $connection, &$result) {
            $session->call($uri, $args)->then(
                function ($res) use ($connection, &$result) {
                    $result = $res[0];
                    $connection->close();
                },
                function ($error) use ($connection, &$result) {
                    $result = $error;
                    $connection->close();
                }
            );
        });

        $connection->open();

        return $result;
    }
} 