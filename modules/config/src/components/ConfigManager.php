<?php

namespace im\config\components;

use im\config\models\Config;
use yii\base\Component;
use Yii;

/**
 * Config Manager.
 *
 * @package im\config\components
 */
class ConfigManager extends Component
{
    /**
     * @var ConfigInterface[] array of configs
     */
    private $_configs = [];

    /**
     * Register config.
     *
     * @param ConfigInterface $config
     */
    public function registerConfig(ConfigInterface $config)
    {
        $this->_configs[$config->getKey()] = $config;
    }

    /**
     * Get config by key.
     *
     * @param string $key
     * @return ConfigInterface|null
     */
    public function getConfig($key)
    {
        return isset($this->_configs[$key]) ? $this->_configs[$key] : null;
    }

    /**
     * Save config.
     *
     * @param ConfigInterface $config
     * @param array $data
     * @return bool
     */
    public function saveConfig(ConfigInterface $config, array $data)
    {
        /** @var Config $config */
        if ($config->load($data) && $config->validate()) {
            $configComponent = $this->getConfigComponent();
            foreach ($config->getAttributes() as $key => $value) {
                $configComponent->set($config->getKey() . '.' . $key, $value);
            }
        }
    }

    /**
     * @param ConfigInterface $config
     * @return ConfigInterface
     */
    public function loadConfig(ConfigInterface $config)
    {
        /** @var Config $config */
        if ($values = $this->getConfigComponent()->get($config->getKey() . '.*')) {
            $configData = [];
            foreach ($values as $key => $value) {
                $configData[str_replace($config->getKey() . '.', '', $key)] = $value;
            }
            $config->load($configData, '');
        }

        return $config;
    }

    /**
     * @return \im\config\components\Config
     */
    public function getConfigComponent()
    {
        return Yii::$app->get('config');
    }
}
