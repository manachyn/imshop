<?php

namespace im\base\types;

use yii\base\Object;
use yii\helpers\Inflector;

class EntityType extends Object
{
    /**
     * @var string
     */
    private $_type;

    /**
     * @var string
     */
    private $_class;

    /**
     * @var string
     */
    private $_group;

    /**
     * @var string
     */
    private $_name;

    /**
     * @var string
     */
    private $_pluralName;

    function __construct($type, $class, $group = null, $name = null, $pluralName = null)
    {
        $this->_type = $type;
        $this->_class = $class;
        $this->_group = $group;
        $this->_name = $name;
        $this->_pluralName = $pluralName;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->_type = $type;
    }

    /**
     * @return string
     */
    public function getClass()
    {
        return $this->_class;
    }

    /**
     * @param string $class
     */
    public function setClass($class)
    {
        $this->_class = $class;
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->_group;
    }

    /**
     * @param string $group
     */
    public function setGroup($group)
    {
        $this->_group = $group;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->_name ?: Inflector::titleize($this->_type);
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return string
     */
    public function getPluralName()
    {
        return $this->_pluralName ?: Inflector::titleize(Inflector::pluralize($this->_type));
    }

    /**
     * @param string $pluralName
     */
    public function setPluralName($pluralName)
    {
        $this->_pluralName = $pluralName;
    }

    public function __toString()
    {
        return $this->getType();
    }
}