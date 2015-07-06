<?php

namespace im\forms\components;

use im\forms\widgets\DynamicActiveField;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Object;
use yii\helpers\ArrayHelper;

/**
 * Class DynamicActiveForm allows to create form fields dynamically without form object using form config.
 *
 * @package im\forms\components
 */
class DynamicActiveForm extends Object
{
    /**
     * @var array form config
     */
    public $config = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->config === null) {
            throw new InvalidConfigException('The "config" property must be set.');
        }

        $this->config = $this->normalizeConfig($this->config);
    }

    /**
     * Generates a dynamic form field.
     *
     * @param Model $model the data model.
     * @param string $attribute the attribute name or expression.
     * @param array $options the additional configurations for the field object.
     * @return DynamicActiveField the created DynamicActiveField object
     */
    public function field($model, $attribute, $options = [])
    {
        $config = ArrayHelper::getValue($this->config, 'fieldConfig', []);

        if ($config instanceof \Closure) {
            $config = call_user_func($config, $model, $attribute);
        }

        if (!isset($config['class'])) {
            $config['class'] = DynamicActiveField::className();
        }

        return \Yii::createObject(ArrayHelper::merge($config, $options, [
            'model' => $model,
            'attribute' => $attribute,
            'formConfig' => $this->config
        ]));
    }

    /**
     * Normalize config.
     *
     * @param array $config
     * @return array
     */
    protected function normalizeConfig($config)
    {
        array_walk_recursive($config, function(&$value) {
            if ($value === 'true') {
                $value = true;
            } elseif ($value === 'false') {
                $value = false;
            }
        });

        return $config;
    }
} 