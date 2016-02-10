<?php

namespace im\messaging;

use yii\base\Component;
use yii\di\Instance;

class MessagingComponent extends Component
{
    /**
     * @var array resolved massaging drivers.
     */
    public $drivers = [];

    /**
     * @var string default driver name.
     */
    public $defaultDriver;

    /**
     * Get a driver instance.
     *
     * @param string $driver
     * @return mixed
     */
    public function getClient($driver = null)
    {
        $driver = $driver ?: $this->defaultDriver;

        return $this->drivers[$driver] = Instance::ensure($this->drivers[$driver], 'im\messaging\WampClientInterface');
    }
} 