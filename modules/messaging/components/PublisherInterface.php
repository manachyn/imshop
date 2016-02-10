<?php

namespace app\modules\messaging\components;

interface PublisherInterface
{
    /**
     * Publish the given message.
     *
     * @param array $channels
     * @param string $message
     * @param array $data
     * @return void
     */
    public function publish(array $channels, $message, array $data = []);
} 