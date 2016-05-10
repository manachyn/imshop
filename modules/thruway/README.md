CONFIGURATION
-------------

### Thruway component

Add component to the file `config/console.php`, for example:

```php
'thruway' => [
    'class' => 'im\thruway\Thruway',
    'realm' => 'realm1',
    'url' => 'ws://127.0.0.1:8081',
    'routerIp' => '127.0.0.1',
    'routerPort' => '8080',
    'routerTrustedPort' => '8081'
]
```

Add records to controller map

```php
[
    'thruway' => 'im\thruway\commands\ThruwayController',
    'thruway-router' => 'im\thruway\commands\ThruwayRouterController',
    'thruway-worker' => 'im\thruway\commands\ThruwayWorkerController'
]
```

#### Start the Thruway process

You can start the default Thruway workers (router and client workers), without any additional configuration.

    $ nohup php yii thruway/start &

By default, the router starts on ws://127.0.0.1:8080

#### To see a list of running processes (workers)

    $ php yii thruway/status

#### Stop a process (worker), i.e. `default`

    $ php yii thruway/stop default

#### Start a process (worker), i.e. `default`

    $ php yii thruway/start default

#### Restart a process (worker), i.e. `default`

    $ php yii thruway/restart default