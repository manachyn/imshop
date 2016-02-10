<?php

namespace app\modules\events\components\broadcasting;

use yii\base\Event;

class BroadcastEventHandler
{
    /**
     * The broadcaster implementation.
     *
     * @var BroadcasterInterface
     */
    protected $broadcaster;

    /**
     * @param BroadcasterInterface $broadcaster
     */
    public function __construct(BroadcasterInterface $broadcaster)
    {
        $this->broadcaster = $broadcaster;
    }

    /**
     * Handle broadcast event.
     *
     * @param Event $event
     * @return void
     */
    public function handle(Event $event)
    {


        $name = method_exists($event, 'broadcastAs')
            ? $event->broadcastAs() : get_class($event);

        $this->broadcaster->broadcast(
            $event->broadcastOn(), $name, $this->getPayloadFromEvent($event)
        );

        $job->delete();
    }
} 