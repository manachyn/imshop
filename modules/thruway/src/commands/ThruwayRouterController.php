<?php

namespace im\thruway\commands;

use im\thruway\Thruway;
use Thruway\Peer\Router;
use Thruway\Transport\RatchetTransportProvider;
use yii\console\Controller;
use Yii;

class ThruwayRouterController extends Controller
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
     * Start the default Thruway WAMP router
     */
    public function actionStart()
    {
        Yii::info('Starting Thruway Router...', 'thruway');

        /** @var Thruway $thruway */
        $thruway = $this->thruway = Yii::$app->get('thruway');

        $router = new Router();
        $transportProvider = new RatchetTransportProvider($thruway->routerIp, $thruway->routerPort);
        $router->addTransportProvider($transportProvider);

        //Trusted provider (bound to loopback and requires no authentication)
        $trustedProvider = new RatchetTransportProvider($thruway->routerIp, $thruway->routerTrustedPort);
        $trustedProvider->setTrusted(true);
        $router->addTransportProvider($trustedProvider);

        $router->start();
    }
} 