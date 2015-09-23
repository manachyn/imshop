<?php

namespace im\base\types;

use yii\base\Component;
use yii\base\InvalidParamException;
use yii\di\Instance;

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

//    /**
//     * @inheritdoc
//     */
//    public function init()
//    {
//        parent::init();
//        foreach ($this->entityTypes as $key => $type) {
//            $this[$key] = Instance::ensure($type, 'im\base\types\EntityType');
//        }
//    }



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
     * Checks whether the type exists in the registry
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
        $entityType = $entityClass;
        foreach ($this->_entityTypes as $type) {
            if ($type->getClass() === $entityClass) {
                $entityType = $type;
                break;
            }
        }

        return $asString ? $entityType->getType() : $entityType;
    }

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