<?php

namespace im\thruway\commands;

use im\thruway\process\Command;
use im\thruway\process\ProcessManager;
use im\thruway\Thruway;
use yii\console\Controller;
use Yii;

class RatchetController extends Controller
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
            //$this->startWorker($worker);
        } else {
            $this->startManager();
        }
    }

    /**
     * Start process manager
     */
    protected function startManager()
    {
        Yii::info('Starting workers...', 'ratchet');

        $this->addWorkers();

        $this->processManager->start();
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

            Yii::info("Adding worker: {$name}", 'ratchet');

            $maxInstances = isset($worker['maxInstances']) ? $worker['maxInstances'] : 1;
            $cmd = "{$phpBinary} {$baseDir}/yii thruway-worker/start {$name} 0";
            $command = new Command($name, $cmd);

            $command->setMaxInstances($maxInstances);
            $this->processManager->addCommand($command);
        }
    }
} 