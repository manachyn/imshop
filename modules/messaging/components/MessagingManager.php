<?php

namespace app\modules\messaging\components;

use yii\base\Component;
use yii\di\Instance;

class MessagingManager extends Component
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
    public function getConnection($driver = null)
    {
        $driver = $driver ?: $this->defaultDriver;

        return $this->drivers[$driver] = Instance::ensure($this->drivers[$driver], 'app\modules\messaging\components\PublisherInterface');
    }
} 