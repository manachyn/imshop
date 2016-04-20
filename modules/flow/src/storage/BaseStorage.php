<?php

namespace im\flow\storage;

/**
 * Class BaseStorage
 * @package im\flow\storage
 */
abstract class BaseStorage implements StorageInterface
{
    /**
     * Storage domain.
     *
     * @var string
     */
    protected $domain;

    /**
     * {@inheritdoc}
     */
    public function initialize($domain)
    {
        $this->domain = $domain;

        return $this;
    }
}
