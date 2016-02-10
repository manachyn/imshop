<?php

namespace im\ratchet;

use yii\base\Component;

class Ratchet extends Component
{
    /**
     * @var string HTTP hostname clients intend to connect to
     */
    public $host = 'localhost';

    /**
     * @var int port to listen on
     */
    public $port = 8080;

    /**
     * @var string IP address to bind to. Default is localhost/proxy only
     * (binding to 127.0.0.1 means the only client that can connect is itself). '0.0.0.0' for any machine.
     */
    public $ip = '127.0.0.1';

    /**
     * @var array endpoints/applications on the WebSocket server
     */
    public $endpoints = [];
} 