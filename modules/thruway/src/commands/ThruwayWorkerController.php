<?php

namespace im\thruway\commands;

use im\thruway\Client;
use im\thruway\Thruway;
use im\thruway\Wamp;
use React\EventLoop\Factory;
use Thruway\Transport\PawlTransportProvider;
use yii\console\Controller;
use Yii;

class ThruwayWorkerController extends Controller
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
     * Start Thruway WAMP worker.
     *
     * @param string $name worker name
     * @param int $instance worker instance number
     */
    public function actionStart($name, $instance = 0)
    {
        /** @var Thruway $thruway */
        $thruway = $this->thruway = Yii::$app->get('thruway');
        $loop = Factory::create();
        $worker = $thruway->getWorker($name);
        $realm = isset($worker['realm']) ? $worker['realm'] : $thruway->realm;
        $url = isset($worker['url']) ? $worker['url'] : $thruway->url;
        $transport = new PawlTransportProvider($url);
        $client = new Client($realm, $loop);
        $client->setProcessInstance($instance);
        $client->addTransportProvider($transport);
        $client->setProviders($thruway->getProviders($name));
        $client->start();
    }
} 