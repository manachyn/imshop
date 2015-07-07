<?php

namespace im\base\components;

use yii\base\Arrayable;
use yii\base\Component;

/**
 * Class ModelSerializer
 * @package im\base\components
 */
abstract class ModelSerializer extends Component
{
    /**
     * This method should return an array of field names or field definitions.
     * @return array
     */
    abstract public function fields();

    /**
     * Serializes the given data into a format that can be easily turned into other formats.
     * @param mixed $data the data to be serialized.
     * @return mixed the converted data.
     */
    public function serialize($data)
    {
        if ($data instanceof Arrayable) {
            return $this->serializeModel($data);
        } elseif (is_array($data)) {
            $result = [];
            foreach ($data as $key => $value) {
                $result[$key] = $this->serialize($value);
            }
            return $result;
        } else {
            return $data;
        }
    }

    /**
     * Serializes a model object.
     * @param Arrayable $model
     * @return array the array representation of the model
     */
    public function serializeModel($model)
    {
        $data = [];
        foreach ($this->fields() as $field => $definition) {
            $data[$field] = is_string($definition) ? $model->$definition : call_user_func($definition, $model, $field);
        }
        return $data;
    }
} 