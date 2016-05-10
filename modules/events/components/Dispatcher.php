<?php

namespace app\modules\events\components;

use yii\base\Component;
use yii\base\Event;

class Dispatcher extends Component
{
    /**
     * @param string $name
     * @param Event $event
     */
    public function trigger($name, Event $event = null)
    {
        $this->broadcastEvent($name, $event);
    }

    protected function broadcastEvent(Event $event)
    {
        if ($this->queueResolver) {
            $connection = $event instanceof ShouldBroadcastNow ? 'sync' : null;

            $this->resolveQueue()->connection($connection)->push('Illuminate\Broadcasting\BroadcastEvent', [
                'event' => serialize($event),
            ]);
        }
    }
} 