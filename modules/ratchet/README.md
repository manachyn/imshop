REQUIREMENTS
------------

AutobahnJS v0.9.0+ requires a WAMPv2 compatible server and is not compatible with Ratchet

CONFIGURATION
-------------

### Ratchet component

Add component to the file `config/console.php`, for example:

```php
'ratchet' => [
    'class' => 'im\ratchet\Ratchet',
    'host' => 'localhost',
    'port' => 8080,
    'ip' => '0.0.0.0',
    'endpoints' => [
        '/events' => 'im\ratchet\EventsEndpoint'
    ]
]
```

Add records to controller map

```php
[
    'ratchet-server' => 'im\ratchet\commands\RatchetServerController',
]
```


#### Start Ratchet websocket server

    $ php yii messaging/wamp-server



https://github.com/ratchetphp/Ratchet

Ratchet + Laravel
http://brainsocket.brainboxmedia.ca/
https://github.com/sidneywidmer/Latchet
https://github.com/BrainBoxLabs/brain-socket
https://medium.com/laravel-4/laravel-4-real-time-chat-eaa550829538




