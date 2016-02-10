<?php

namespace app\modules\messaging\commands;

use app\modules\messaging\components\drivers\ratchet\WampServer;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\Wamp\WampServer as RatchetWampServer;
use Ratchet\WebSocket\WsServer;
use React\EventLoop\Factory;
use React\Socket\Server;
use React\ZMQ\Context;
use yii\console\Controller;

class WampServerController extends Controller
{
    /**
     * @inheritdoc
     */
    public $defaultAction = 'run';

//    /**
//     * @param int $port the port to server sockets on
//     */
//    public function actionRun($port = 8080)
//    {
//        $loop   = Factory::create();
//        $server = new WampServer();
//
//        // Listen for the web server to make a ZeroMQ push after an ajax request
//        $context = new Context($loop);
//        $pull = $context->getSocket(\ZMQ::SOCKET_PULL);
//        $pull->bind('tcp://127.0.0.1:5555'); // Binding to 127.0.0.1 means the only client that can connect is itself
//        $pull->on('message', array($server, 'onMessage'));
//
//        // Set up our WebSocket server for clients wanting real-time updates
//        $webSock = new Server($loop);
//        $webSock->listen(8080, '0.0.0.0'); // Binding to 0.0.0.0 means remotes can connect
//        $webServer = new IoServer(
//            new HttpServer(
//                new WsServer(
//                    new RatchetWampServer(
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
     * @param int $port the port to server sockets on
     */
    public function actionRun($port = 8080)
    {
        $server = new \app\modules\messaging\components\drivers\thruway\WampServer();
        $server->start();
    }
} 