<?php

namespace im\eav\components;

/**
 * Interface implemented by object which can be characterized using the attributes.
 */
interface AttributesHolderInterface
{
    /**
     * Returns all attributes of the entity.
     *
     * @return AttributeValueInterface[]
     */
    public function getEAttributes();

    /**
     * Sets all attributes of the entity.
     *
     * @param AttributeValueInterface[] $attributes
     */
    public function setEAttributes($attributes);

    /**
     * Sets one attribute by name.
     *
     * @param string $name
     * @param mixed $value
     */
    public function setEAttribute($name, $value);

    /**
     * Adds an attribute to the entity.
     *
     * @param AttributeValueInterface $attribute
     */
    public function addEAttribute(AttributeValueInterface $attribute);

    /**
     * Removes an attribute from the entity.
     *
     * @param AttributeValueInterface $attribute
     */
    public function removeEAttribute(AttributeValueInterface $attribute);

    /**
     * Removes an attribute from the entity by name.
     *
     * @param string $name
     */
    public function removeEAttributeByName($name);

    /**
     * Checks whether the entity has a given attribute.
     *
     * @param AttributeValueInterface $attribute
     * @return Boolean
     */
    public function hasEAttribute(AttributeValueInterface $attribute);

    /**
     * Checks whether the entity has a given attribute, access by name.
     *
     * @param string $name
     * @return Boolean
     */
    public function hasEAttributeByName($name);

    /**
     * Returns an attribute of the entity by its name.
     *
     * @param string $name
     *
     * @return AttributeValueInterface
     */
    public function getEAttribute($name);
} 