<?php

namespace im\catalog\components;

use im\eav\components\AttributeInterface;
use im\variation\components\OptionInterface;


/**
 * The product type defines the template for new products to be created from
 * which these product will inherit options and attributes from an instance of this class.
 */
interface ProductTypeInterface
{
    /**
     * Returns all prototype attributes.
     *
     * @return AttributeInterface[]
     */
    public function getEAttributes();

    /**
     * Sets all prototype attributes.
     *
     * @param AttributeInterface[] $attributes
     */
    public function setEAttributes($attributes);

    /**
     * Adds attribute.
     *
     * @param AttributeInterface $attribute
     */
    public function addEAttribute(AttributeInterface $attribute);

    /**
     * Removes attribute from prototype.
     *
     * @param AttributeInterface $attribute
     */
    public function removeEAttribute(AttributeInterface $attribute);

    /**
     * Checks whether prototype has given attribute.
     *
     * @param AttributeInterface $attribute
     *
     * @return Boolean
     */
    public function hasEAttribute(AttributeInterface $attribute);

    /**
     * Returns all prototype options.
     *
     * @return OptionInterface[]
     */
    public function getOptions();

    /**
     * Sets all prototype options.
     *
     * @param OptionInterface[] $options
     */
    public function setOptions($options);

    /**
     * Adds option.
     *
     * @param OptionInterface $option
     */
    public function addOption(OptionInterface $option);

    /**
     * Removes option from prototype.
     *
     * @param OptionInterface $option
     */
    public function removeOption(OptionInterface $option);

    /**
     * Checks whether prototype has given option.
     *
     * @param OptionInterface $option
     *
     * @return boolean
     */
    public function hasOption(OptionInterface $option);

    /**
     * @return boolean
     */
    public function hasParent();

    /**
     * @param ProductTypeInterface|null $parent
     */
    public function setParent(ProductTypeInterface $parent = null);

    /**
     * @return ProductTypeInterface|null
     */
    public function getParent();
}