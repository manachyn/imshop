<?php

namespace im\base\components;

use yii\base\Component;
use yii\base\InvalidParamException;

class EntityTypesRegister extends Component
{
    /**
     * @var array entity types
     */
    public $entityTypes = [];


    public function registerEntityType($type, $class)
    {
        $this->entityTypes[$type] = $class;
    }

    /**
     * @param mixed $entity entity object or class name
     * @return string entity type
     * @throws InvalidParamException
     */
    public function getEntityType($entity)
    {
        $entityClass = is_object($entity) ? get_class($entity) : $entity;
        $entityType = null;
        foreach ($this->entityTypes as $type => $class) {
            if ($class == $entityClass) {
                $entityType = $type;
                break;
            }
        }
        if ($entityType === null)
            throw new InvalidParamException("Type is not registered for '$entityClass'");

        return $entityType;
    }

    public function getEntityClass($type)
    {
        if (isset($this->entityTypes[$type])) {
            return $this->entityTypes[$type];
        } elseif (class_exists($type)) {
            return $type;
        } else {
            throw new InvalidParamException("Entity type '$type' is not registered");
        }
    }
}