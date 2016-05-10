<?php

namespace app\modules\messaging\components\drivers\ratchet;

use app\modules\messaging\components\PublisherInterface;

class Publisher implements PublisherInterface
{
    public $port = 8080;

    /**
     * @inheritdoc
     */
    public function publish(array $channels, $message, array $data = [])
    {
        $data['name'] = $message;
        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'wamp socket');
        $socket->connect("tcp://localhost:5555");
        $socket->send(json_encode($data));

//        $payload = json_encode(['event' => $event, 'data' => $payload]);
//
//        foreach ($channels as $channel) {
//            $connection->publish($channel, $payload);
//        }
    }
}