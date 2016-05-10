<?php

namespace im\thruway;

class EventsProvider extends Provider
{
    protected $prefix = 'com.example.';

    /**
     * @inheritdoc
     */
    public function boot()
    {
        $this->register('trigger', [$this, 'trigger']);
    }

    /**
     * @param array $args
     * @param \stdClass $data
     * @return bool
     */
    public function trigger($args, $data)
    {
        $this->publish('com.example.' . $args[0], $args, $data);
        return true;
    }
} 