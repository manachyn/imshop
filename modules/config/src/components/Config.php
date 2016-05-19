<?php

namespace im\config\components;

use Yii;
use yii\base\Component;
use yii\di\Instance;
use yii\web\User;

/**
 * Class Config
 * @package im\config\components
 */
class Config extends Component
{
    /**
     * @var ConfigProviderInterface|array|string
     */
    public $provider = ['class' => 'im\config\components\DBConfigProvider'];

    /**
     * @var User|array|string
     */
    public $user = 'user';

    /**
     * @var ConfigManager|array|string
     */
    public $configManager;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->provider = Instance::ensure($this->provider, ConfigProviderInterface::class);
        $this->user = Instance::ensure($this->user, User::class);
        $this->configManager = Instance::ensure($this->configManager, ConfigManager::class);
    }

    /**
     * Return config value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $value = $this->provider->get($key, $this->getContext($key), $default);

        // Get default values from config class
        if ($config = $this->getConfig($key)) {
            $clearKey = $this->getKey($key);
            if ($clearKey == '*') {
                $default = array_filter($config->getOptions(), function($item) {
                    return $item !== null;
                });
                $value = $value ?: [];
                $value = array_merge(array_combine(array_map(function($v) use ($config) {
                    return $config->getKey() . '.' . $v;
                }, array_keys($default)), array_values($default)), $value);
            } elseif (!$value) {
                $value = $config->$clearKey;
            }
        }

        return $value;
    }

    /**
     * Set config value.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        return $this->provider->set($key, $value, $this->getContext($key));
    }

    /**
     * Check whether config key exists.
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return $this->provider->has($key, $this->getContext($key));
    }

    /**
     * Remove config value by key
     *
     * @param string $key
     * @return mixed The previous value
     */
    public function remove($key)
    {
        return $this->provider->remove($key, $this->getContext($key));
    }

    /**
     * @param string $key
     * @return string
     */
    protected function normalizeKey($key)
    {
        if ($config = $this->getConfig($key)) {
            if (in_array($this->getKey($key), $config->getUserSpecificOptions()) && $this->user->identity) {
                $key = 'user' . $this->user->identity->getId() . '.' . $key;
            }
        }

        return $key;
    }

    /**
     * Get registered config by key.
     *
     * @param string $key
     * @return ConfigInterface|null
     */
    protected function getConfig($key)
    {
        return $this->configManager->getConfig(explode('.', $key)[0]);
    }

    /**
     * Get key without first part of nested key.
     *
     * @param string $key
     * @return array
     */
    protected function getKey($key)
    {
        $parts = explode('.', $key);

        return count($parts) > 1 ? implode('.', array_slice($parts, 1)) : $key;
    }

    /**
     * Get context by config key.
     *
     * @param string $key
     * @return null|string
     */
    protected function getContext($key)
    {
        $context = null;
        if ($config = $this->getConfig($key)) {
            $clearKey = $this->getKey($key);
            $options = $config->getUserSpecificOptions();
            if ($options && ($clearKey == '*' || in_array($clearKey, $options)) && $this->user->identity) {
                $context = 'user-' . $this->user->identity->getId();
            }
        }

        return $context;
    }
}
