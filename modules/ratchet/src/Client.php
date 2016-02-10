<?php

namespace im\ratchet;

use im\messaging\WampClientInterface;

class Client implements WampClientInterface
{
    public $zmqPort = 5555;

    /**
     * ZMQSocket instance
     *
     * @var \ZMQSocket $socket
     */
    protected $socket;

    /**
     * Publish the given message.
     *
     * @param array $channel
     * @param array $message
     * @return void
     */
    public function publish(array $channel, $message)
    {
        $message = array_merge(array('topic' => $channel), (array) $message);
        $this->getSocket()->send(json_encode($message));
    }

    /**
     * Get zmqSocket to push messages.
     *
     * @return \ZMQSocket instance
     */
    protected function getSocket()
    {
        if(isset($this->socket)) {
            return $this->socket;
        } else {
            return $this->connectZmq();
        }
    }

    /**
     * Connect to socket.
     *
     * @return \ZMQSocket instance
     */
    protected function connectZmq()
    {
        $context = new \ZMQContext();
        $this->socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'ratchet.push');
        $this->socket->connect('tcp://localhost:' . $this->zmqPort);

        return $this->socket;
    }
}