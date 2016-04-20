<?php

namespace im\flow\storage;

/**
 * Interface StorageInterface
 * @package im\flow\storage
 */
interface StorageInterface
{
    /**
     * Initializes storage for given domain.
     *
     * @param string $domain
     *
     * @return $this
     */
    public function initialize($domain);

    /**
     * Checks if the storage has a value for a key.
     *
     * @param string $key A unique key
     *
     * @return bool Whether the storage has a value for this key
     */
    public function has($key);

    /**
     * Returns the value for a key.
     *
     * @param string $key     A unique key
     * @param mixed  $default
     *
     * @return mixed|null The value in the storage or default if set or null if not found
     */
    public function get($key, $default = null);

    /**
     * Sets a value in the storage.
     *
     * @param string $key   A unique key
     * @param string $value The value to storage
     */
    public function set($key, $value);

    /**
     * Removes a value from the storage.
     *
     * @param string $key A unique key
     */
    public function remove($key);

    /**
     * Clears all values from current domain.
     */
    public function clear();
}
