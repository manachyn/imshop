<?php

namespace im\flow\storage;

use Yii;
use yii\web\Session;

/**
 * Class SessionStorage
 * @package im\flow\storage
 */
class SessionStorage extends BaseStorage
{
    /**
     * Session.
     *
     * @var Session
     */
    protected $session;

    /**
     * SessionStorage constructor.
     */
    public function __construct()
    {
        $this->session = Yii::$app->session;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        return $this->session->get($this->resolveKey($key), $default);
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $this->session->set($this->resolveKey($key), $value);
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        return $this->session->has($this->resolveKey($key));
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        $this->session->remove($this->resolveKey($key));
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->session->remove($this->domain);
    }

    /**
     * Resolve key for current domain.
     *
     * @param string $key
     *
     * @return string
     */
    private function resolveKey($key)
    {
        return $this->domain . '/' . $key;
    }
}
