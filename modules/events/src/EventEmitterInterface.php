<?php

namespace im\events;

interface EventEmitterInterface
{
    public function on($event, callable $listener);
    public function once($event, callable $listener);
    public function removeListener($event, callable $listener);
    public function removeAllListeners($event = null);
    public function listeners($event);
    public function emit($event, array $arguments = []);
}
