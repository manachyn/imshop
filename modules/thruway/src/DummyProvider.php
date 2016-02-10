<?php

namespace im\thruway;

class DummyProvider extends Provider
{
    protected $prefix = 'com.example.';

    /**
     * @inheritdoc
     */
    public function boot()
    {
        $this->register('add', [$this, 'add']);
        $this->register('greet', [$this, 'greet']);
        $this->subscribe('com.example.greeting', [$this, 'onGreeting']);
    }

    /**
     * @param array $args
     * @return integer
     */
    public function add($args)
    {
        return $args[0] + $args[1];
    }

    /**
     * @param array $args
     * @param \stdClass $data
     * @return bool
     */
    public function greet($args, $data)
    {
        $this->publish('com.example.greeting', [$args[0]], ['to' => $data->to]);
        return true;
    }

    public function onGreeting()
    {
        var_dump(func_get_args());
    }
} 