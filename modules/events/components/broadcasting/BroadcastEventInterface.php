<?php

namespace app\modules\events\components\broadcasting;

interface BroadcastEventInterface
{
    /**
     * Get the channels the event should broadcast on.
     *
     * @return array
     */
    public function getChannels();
} 