<?php

namespace im\eav\components;

interface AttributeValueInterface
{
    /**
     * Get entity.
     *
     * @return AttributesHolderInterface
     */
    public function getEntity();

    /**
     * Set entity.
     *
     * @param AttributesHolderInterface|null $entity
     */
    public function setEntity(AttributesHolderInterface $entity = null);

    /**
     * Get attribute.
     *
     * @return AttributeInterface
     */
    public function getEAttribute();

    /**
     * Set attribute.
     *
     * @param AttributeInterface $attribute
     */
    public function setEAttribute(AttributeInterface $attribute);

    /**
     * Get attribute value.
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Set attribute value.
     *
     * @param mixed $value
     */
    public function setValue($value);

    /**
     * Get attribute name.
     *
     * @return string
     */
    public function getName();

    /**
     * Get attribute presentation.
     *
     * @return string
     */
    public function getPresentation();

    /**
     * The type of the attribute.
     *
     * @return string
     */
    public function getType();

    /**
     * The unit of the attribute value.
     *
     * @return string
     */
    public function getUnit();
}
