<?php

namespace app\modules\messaging\components\drivers\thruway;

use Thruway\Peer\Router;
use Thruway\Transport\RatchetTransportProvider;

class WAMPRouter
{
    public function start()
    {
        $router = new Router();

        $transportProvider = new RatchetTransportProvider("127.0.0.1", 9090);

        $router->addTransportProvider($transportProvider);

        $router->start();
    }
} 