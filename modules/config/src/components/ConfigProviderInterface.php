<?php

namespace im\config\components;

/**
 * Interface ConfigProviderInterface
 * @package im\config\components
 */
interface ConfigProviderInterface extends \ArrayAccess, \IteratorAggregate
{
    /**
     * Returns the value/s given a key or array of keys from config
     *
     * @param string|array $key
     * @param mixed $default The default value in case the key does not exist
     *
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Set a value to config
     *
     * @param string $key   The key
     * @param mixed  $value The value corresponding to the key
     */
    public function set($key, $value);

    /**
     * Tells if config contains `$key`
     *
     * @param string $key
     *
     * @return Boolean
     */
    public function has($key);

    /**
     * Removes a value given a key
     *
     * @param string $key
     *
     * @return mixed The previous value
     */
    public function remove($key);

    /**
     * Returns all values set in the config
     *
     * @return array
     */
    public function all();
}
