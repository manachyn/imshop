<?php

namespace im\ratchet;

use im\events\EventEmitterInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Ratchet\Wamp\WampServerInterface;


class EventsEndpoint implements WampServerInterface
{
    /**
     * @var Topic[] a lookup of all the topics clients have subscribed to
     */
    protected $subscribedTopics = array();

    /**
     * @var EventEmitterInterface event emitter
     */
    protected $emitter;

    /**
     * @var array
     */
    protected $registeredTopics = array();

    /**
     * @param EventEmitterInterface $emitter
     */
    public function __construct(EventEmitterInterface $emitter)
    {
        $this->emitter = $emitter;
    }

    /**
     * @inheritdoc
     */
    function onOpen(ConnectionInterface $conn)
    {
        $this->registeredTopics['com.example.trigger'] = [$this, 'trigger'];
    }

    /**
     * @inheritdoc
     */
    function onClose(ConnectionInterface $conn)
    {
    }

    /**
     * @inheritdoc
     */
    function onError(ConnectionInterface $conn, \Exception $e)
    {
    }

    /**
     * @inheritdoc
     */
    function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        echo 'onCall', PHP_EOL;
        $args = func_get_args();
        array_shift($args);
        print_r($args);

        if (isset($this->registeredTopics[$topic->getId()])) {
            call_user_func_array($this->registeredTopics[$topic->getId()], $params);
        }
    }

    /**
     * @inheritdoc
     */
    function onSubscribe(ConnectionInterface $conn, $topic)
    {
        $this->subscribedTopics[$topic->getId()] = $topic;
    }

    /**
     * @inheritdoc
     */
    function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
    }

    /**
     * @inheritdoc
     */
    function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
    }

    public function trigger($event, $data)
    {
        $topic = $this->subscribedTopics[$event];
        $topic->broadcast($data);
    }

    /**
     * @param string $event json string we'll receive from ZeroMQ
     */
    public function onEvent($event) {

        $eventData = json_decode($event, true);

        if (!array_key_exists($eventData['name'], $this->subscribedTopics)) {
            return;
        }

        $topic = $this->subscribedTopics[$eventData['name']];

        // Resend the data to all the clients subscribed to that event
        $topic->broadcast($eventData);
    }
}