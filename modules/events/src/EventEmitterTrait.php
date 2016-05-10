<?php

namespace im\events;

trait EventEmitterTrait
{
    protected $listeners = [];

    public function on($event, callable $listener)
    {
        if (!isset($this->listeners[$event])) {
            $this->listeners[$event] = [];
        }

        $this->listeners[$event][] = $listener;
    }

    public function once($event, callable $listener)
    {
        $onceListener = function () use (&$onceListener, $event, $listener) {
            $this->removeListener($event, $onceListener);

            call_user_func_array($listener, func_get_args());
        };

        $this->on($event, $onceListener);
    }

    public function removeListener($event, callable $listener)
    {
        if (isset($this->listeners[$event])) {
            $index = array_search($listener, $this->listeners[$event], true);
            if (false !== $index) {
                unset($this->listeners[$event][$index]);
            }
        }
    }

    public function removeAllListeners($event = null)
    {
        if ($event !== null) {
            unset($this->listeners[$event]);
        } else {
            $this->listeners = [];
        }
    }

    public function listeners($event)
    {
        return isset($this->listeners[$event]) ? $this->listeners[$event] : [];
    }

    public function emit($event, array $arguments = [])
    {
        foreach ($this->listeners($event) as $listener) {
            call_user_func_array($listener, $arguments);
        }
    }
}
