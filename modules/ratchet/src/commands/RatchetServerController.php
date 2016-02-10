<?php

namespace im\ratchet\commands;

use im\ratchet\Ratchet;
use im\ratchet\WampServer;
use Ratchet\ComponentInterface;
use React\EventLoop\Factory;
use React\ZMQ\Context;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use Yii;
use yii\helpers\Console;

class RatchetServerController extends Controller
{
    /**
     * @var string HTTP hostname clients intend to connect to
     */
    public $host;

    /**
     * @var string port to listen on
     */
    public $port;

    /**
     * @var string IP address to bind to. Default is localhost/proxy only. '0.0.0.0' for any machine.
     */
    public $ip;

    /**
     * @inheritdoc
     */
    public $defaultAction = 'start';

    /**
     * @var array endpoints/applications on the WebSocket server
     */
    protected $endpoints;

    /**
     * @var Ratchet
     */
    protected $ratchet;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->ratchet = Yii::$app->get('ratchet');

        $this->host = $this->host ?: $this->ratchet->host;
        $this->port = $this->port ?: $this->ratchet->port;
        $this->ip = $this->ip ?: $this->ratchet->ip;
        $this->endpoints = $this->endpoints ?: $this->ratchet->endpoints;
    }

//    /**
//     * Start WAMP worker.
//     *
//     * @param string $name worker name
//     * @param int $instance worker instance number
//     */
//    public function actionStart($name, $instance = 0)
//    {
//        $loop = Factory::create();
//        $server = new WampServer();
//
////        // Listen for the web server to make a ZeroMQ push
////        $context = new \React\ZMQ\Context($loop);
////        $pull = $context->getSocket(\ZMQ::SOCKET_PULL);
////        $pull->bind('tcp://127.0.0.1:5555'); // Binding to 127.0.0.1 means the only client that can connect is itself
////        $pull->on('message', array($server, 'onMessage'));
//
//        // Set up our WebSocket server for clients wanting real-time updates
//        $webSock = new \React\Socket\Server($loop);
//        $webSock->listen(8081, '0.0.0.0'); // Binding to 0.0.0.0 means remotes can connect
//        $webServer = new \Ratchet\Server\IoServer(
//            new \Ratchet\Http\HttpServer(
//                new \Ratchet\WebSocket\WsServer(
//                    new \Ratchet\Wamp\WampServer(
//                        $server
//                    )
//                )
//            ),
//            $webSock
//        );
//
//        $loop->run();
//    }


    /**
     * Start the WebSocket server
     */
    public function actionStart()
    {
        if (!$this->endpoints) {
            throw new InvalidConfigException('There are no registered endpoints on the server');
        }

        $this->stdout('Starting Ratchet WebSocket server' . PHP_EOL);

        $app = new \Ratchet\App($this->host, $this->port, $this->ip);

        foreach ($this->endpoints as $path => $endpoint) {
            /** @var ComponentInterface $endpoint */
            $endpoint = Yii::createObject($endpoint);
            $app->route($path, $endpoint, ['*']);
        }

        $this->stdout('Listening on port ' . $this->port . PHP_EOL);

        $app->run();
    }
} 