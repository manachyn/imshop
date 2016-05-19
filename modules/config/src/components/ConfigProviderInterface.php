<?php

namespace im\config\components;

/**
 * Interface ConfigProviderInterface
 * @package im\config\components
 */
interface ConfigProviderInterface
{
    /**
     * Returns the value/s given a key or array of keys from config.
     *
     * @param string|array $key
     * @param string $context
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $context = null, $default = null);

    /**
     * Set a value to config.
     *
     * @param string $key
     * @param mixed $value
     * @param string $context
     * @return
     */
    public function set($key, $value, $context = null);

    /**
     * Whether config exists.
     *
     * @param string $key
     * @param string $context
     * @return bool
     */
    public function has($key, $context = null);

    /**
     * Removes a value given a key
     *
     * @param string $key
     * @param string $context
     * @return mixed The previous value
     */
    public function remove($key, $context = null);
}
