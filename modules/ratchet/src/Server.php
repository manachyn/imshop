<?php

namespace im\ratchet;

use Ratchet\App;
use Ratchet\Http\HttpServer;
use Ratchet\Http\Router;
use Ratchet\Server\FlashPolicy;
use Ratchet\Server\IoServer;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;
use React\Socket\Server as Reactor;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class Server
 * @package im\ratchet
 */
class Server extends App
{
    public function __construct($httpHost = 'localhost', $port = 8080, $address = '127.0.0.1', LoopInterface $loop = null) {
//        if (extension_loaded('xdebug')) {
//            trigger_error('XDebug extension detected. Remember to disable this if performance testing or going live!', E_USER_WARNING);
//        }

        if (3 !== strlen('✓')) {
            throw new \DomainException('Bad encoding, length of unicode character ✓ should be 3. Ensure charset UTF-8 and check ini val mbstring.func_autoload');
        }

        if (null === $loop) {
            $loop = Factory::create();
        }

        $this->httpHost = $httpHost;
        $this->port = $port;

        $socket = new Reactor($loop);
        $socket->listen($port, $address);

        $this->routes  = new RouteCollection;
        $this->_server = new IoServer(new HttpServer(new Router(new UrlMatcher($this->routes, new RequestContext))), $socket, $loop);

        $policy = new FlashPolicy;
        $policy->addAllowedAccess($httpHost, 80);
        $policy->addAllowedAccess($httpHost, $port);
        $flashSock = new Reactor($loop);
        $this->flashServer = new IoServer($policy, $flashSock);
        if (80 == $port) {
            $flashSock->listen(843, '0.0.0.0');
        } else {
            $flashSock->listen(8843);
        }
    }
}