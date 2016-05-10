<?php

namespace im\thruway;

/**
 * @method \React\Promise\Promise subscribe(string $topicName, \Closure $callback, array $options = null)
 * @method \React\Promise\Promise publish(string $topicName, mixed $arguments, mixed $argumentsKw = null, mixed $options = null)
 * @method \React\Promise\Promise call(string $procedureName, mixed $arguments = null, mixed $argumentsKw = null, mixed $options = null)
 */
abstract class Provider
{
    /**
     * @var \Thruway\Peer\Client
     */
    protected $client;

    /**
     * @var string the topic prefix
     */
    protected $prefix = '';

    /**
     * @var string the worker name
     */
    protected $worker = 'default';

    /**
     * @var int
     */
    protected $workerInstance = 0;

    /**
     * @param Client $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Boot this provider. This is the best place to have
     * your subscriptions/registrations for your RPCs and PubSub.
     *
     * @return void
     */
    abstract public function boot();

    /**
     * @param Client $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param int $workerInstance
     */
    public function setWorkerInstance($workerInstance)
    {
        $this->workerInstance = $workerInstance;
    }

    /**
     * @return int
     */
    public function getWorkerInstance()
    {
        return $this->workerInstance;
    }

//    /**
//     * Subscribe to a topic and specify a callback for it.
//     *
//     * @param string $topicName the topic name.
//     * @param string|\Closure $callback the callable to be called when the topic received a publish.
//     * @param array $options Will be passed straight to thruway
//     * @return \React\Promise\Promise
//     * @see \Thruway\ClientSession::subscribe
//     */
//    public function subscribe($topicName, $callback, $options = null)
//    {
//        return $this->client->getSession()->subscribe($this->normalizeName($topicName), $callback, $options);
//    }

    /**
     * Register a RPC.
     *
     * @param string $procedureName
     * @param \Closure $callback
     * @param array|mixed $options
     * @return \React\Promise\Promise
     * @see \Thruway\ClientSession::subscribe
     */
    public function register($procedureName, $callback, $options = null)
    {
        // If this isn't the first worker process to be created, we can't register this RPC call again.
        if ($this->workerInstance > 0 && empty($options['thruway_multiregister'])) {
            return;
        }

        return $this->client->getSession()->register($this->normalizeName($procedureName), $callback, $options);
    }

    /**
     * Override to redirect non-existing calls to the client session.
     * This allows calling client methods directly from the provider which is useful
     * when passing the Client to a registered Closure.
     *
     * @param string $name
     * @param array $arguments
     *
     * @return string
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->getClient()->getSession(), $name], $arguments);
    }

    /**
     * Add prefix to the name.
     *
     * @param string $name
     * @return string
     */
    protected function normalizeName($name)
    {
        return $this->prefix . $name;
    }
} 