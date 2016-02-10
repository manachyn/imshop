<?php

namespace app\modules\events\components\broadcasting;

interface BroadcasterInterface
{
    /**
     * Broadcast the given event.
     *
     * @param array $channels
     * @param string $event
     * @param array $payload
     * @return void
     */
    public function broadcast(array $channels, $event, array $payload = []);
}