<?php

namespace im\thruway;

class Client extends \Thruway\Peer\Client
{
    /**
     * The registered providers.
     *
     * @var array
     */
    protected $providers = [];

    /**
     * @var int
     */
    protected $processInstance = 0;

    /**
     * @inheritdoc
     */
    public function onSessionStart($session, $transport)
    {
        $this->getManager()->setQuiet(true);
        $this->bootProviders();
    }

    /**
     * Boot up the registered providers by calling their boot() method.
     *
     * @return void
     */
    private function bootProviders()
    {
        foreach ($this->providers as $provider) {
            if ($provider instanceof \Closure) {
                $provider($this);
            } else {
                /** @var Provider $provider */
                $provider = new $provider($this);
                $provider->setWorkerInstance($this->processInstance);
                $provider->boot();
            }
        }
    }

    /**
     * @param array $providers
     */
    public function setProviders($providers)
    {
        $this->providers = $providers;
    }

    /**
     * @return array
     */
    public function getProviders()
    {
        return $this->providers;
    }

    /**
     * @param int $processInstance
     */
    public function setProcessInstance($processInstance)
    {
        $this->processInstance = $processInstance;
    }

    /**
     * @return int
     */
    public function getProcessInstance()
    {
        return $this->processInstance;
    }
} 