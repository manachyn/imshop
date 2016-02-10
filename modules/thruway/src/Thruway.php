<?php

namespace im\thruway;

use yii\base\Component;
use yii\base\InvalidParamException;
use yii\di\Instance;

class Thruway extends Component
{
    /**
     * @var string
     */
    public $realm;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $routerIp;

    /**
     * @var string
     */
    public $routerPort;

    /**
     * @var string
     */
    public $routerTrustedPort;

    /**
     * @var string internal URL that does not require authentication
     */
    public $trustedUrl = 'ws://127.0.0.1:8080';

    /**
     * @var string
     */
    public $defaultWorker = 'default';

    /**
     * @var array
     */
    public $workers = [];

    /**
     * @var array
     */
    public $providers = [];

    /**
     * Return worker config by name.
     *
     * @param $name
     * @return array
     * @throws \yii\base\InvalidParamException
     */
    public function getWorker($name)
    {
        if (isset($this->workers[$name])) {
            return $this->workers[$name];
        }

        throw new InvalidParamException("Worker $name is not found");
    }

    /**
     * @param \Closure|string|array $provider
     * @param string $worker
     */
    public function registerProvider($provider, $worker = '')
    {
        $worker = $worker ?: $this->defaultWorker;

        if (!$provider instanceof \Closure) {
            $provider = Instance::ensure($provider, 'im\thruway\Provider');
        }

        $this->providers[$worker][] = $provider;
    }

    /**
     * @param string $worker
     * @return array
     */
    public function getProviders($worker = '')
    {
        $worker = $worker ?: $this->defaultWorker;

        return isset($this->providers[$worker]) ? $this->providers[$worker] : [];
    }
} 