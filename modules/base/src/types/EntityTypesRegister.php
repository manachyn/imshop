<?php

namespace im\base\types;

use yii\base\Component;
use yii\base\InvalidParamException;

class EntityTypesRegister extends Component
{
    /**
     * @var EntityType[] entity types
     */
    private $_entityTypes = [];

    /**
     * Returns entity types.
     *
     * @param string $group
     * @return EntityType[]
     */
    public function getEntityTypes($group = null)
    {
        if ($group) {
            $types = [];
            foreach ($this->_entityTypes as $type) {
                if ($type->getGroup() === $group) {
                    $types[] = $type;
                }
            }
            return $types;
        } else {
            return $this->_entityTypes;
        }
    }

    /**
     * Registers entity type.
     *
     * @param EntityType $type
     */
    public function registerEntityType($type)
    {
        if ($this->hasEntityType($type)) {
            throw new InvalidParamException("Type '{$type->getType()}' is registered already.");
        }
        $this->_entityTypes[$type->getType()] = $type;
    }

    /**
     * Checks whether the type exists in the registry.
     *
     * @param EntityType|string $type
     * @return bool
     */
    public function hasEntityType($type)
    {
        return isset($this->_entityTypes[is_string($type) ? $type : $type->getType()]);
    }

    /**
     * Returns entity type for object or class.
     *
     * @param object|string $entity
     * @param bool $asString
     * @return string
     */
    public function getEntityType($entity, $asString = true)
    {
        $entityClass = is_object($entity) ? get_class($entity) : $entity;
        foreach ($this->_entityTypes as $type) {
            if ($type->getClass() === $entityClass) {
                $entityType = $type;
                break;
            }
        }
        if (!isset($entityType)) {
            $entityType = new EntityType($entityClass, $entityClass);
        }

        return $asString ? $entityType->getType() : $entityType;
    }

    /**
     * Returns entity class by entity type.
     *
     * @param string $type
     * @return string
     */
    public function getEntityClass($type)
    {
        if (isset($this->_entityTypes[$type])) {
            return $this->_entityTypes[$type]->getClass();
        } elseif (class_exists($type)) {
            return $type;
        } else {
            throw new InvalidParamException("Entity type '$type' is not registered");
        }
    }
}