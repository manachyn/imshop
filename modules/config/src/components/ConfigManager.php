<?php

namespace im\config\components;

use yii\base\Component;
use yii\base\DynamicModel;
use yii\base\InvalidParamException;
use Yii;

/**
 * Config Manager.
 *
 * @package im\config\components
 */
class ConfigManager extends Component
{
    /**
     * @var string base interface for all configurable components
     */
    private $_configurableInterface = 'im\config\components\ConfigurableInterface';

    /**
     * @var ConfigurableInterface[] array of configurable components
     */
    private $_configurableComponents = [];

    /**
     * Register configurable component
     * @param ConfigurableInterface $component
     * @throws \yii\base\InvalidParamException
     */
    public function registerConfigurableComponent($component) {
        if (!is_subclass_of($component, $this->_configurableInterface))
            throw new InvalidParamException("Class " . get_class($component) . " must implement $this->_configurableInterface");
        $this->_configurableComponents[] = $component;
    }

    public function getComponent($key) {
        foreach ($this->_configurableComponents as $component) {
            if ($component->getConfigKey() == $key)
                return $component;
        }
        return null;
    }

    /**
     * @param ConfigurableInterface $component
     * @return DynamicModel
     */
    public function getComponentConfigModel($component) {
        $editableAttributes = $component->getEditableAttributes();
        $model = new DynamicModel(array_keys($editableAttributes));
        $model->addRule(array_keys($editableAttributes), 'safe');
        foreach ($editableAttributes as $attribute => $settings) {
            if (isset($settings['rules'])) {
                foreach ($settings['rules'] as $rule) {
                    if (!is_array($rule))
                        $rule = [$rule];
                    $model->addRule($attribute, array_shift($rule), $rule);
                }
            }
        }
        return $model;
    }

    /**
     * @param ConfigurableInterface $component
     * @return array
     */
    public function getComponentConfig($component) {
        /** @var ConfigProviderInterface $configProvider */
        $configProvider = Yii::$app->get('config');
        $editableAttributes = $component->getEditableAttributes();
        $componentConfigKey = $component->getConfigKey();
        $componentConfig = $configProvider->get(array_map(function ($key) use ($componentConfigKey) {
            return $componentConfigKey . '.' . $key;
        }, array_keys($editableAttributes)));
        foreach ($componentConfig as $key => $value) {
            $componentConfig[str_replace($componentConfigKey . '.', '', $key)] = $value;
            unset($componentConfig[$key]);
        }
        return $componentConfig;
    }

    /**
     * @param ConfigurableInterface $component
     * @param array $config
     */
    public function setComponentConfig($component, $config) {
        /** @var ConfigProviderInterface $config */
        $configProvider = Yii::$app->get('config');
        $configKey = $component->getConfigKey();
        foreach ($config as $name => $value) {
            $configProvider->set($configKey . '.' . $name, $value);
        }
    }
} 