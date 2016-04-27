<?php

namespace im\variation\components;

/**
 * Entity option interface.
 *
 * Model implementing this interface represents the option type, which can be attached to an entity.
 *
 */
interface OptionInterface
{
    /**
     * Get internal name.
     *
     * @return string
     */
    public function getName();

    /**
     * Set internal name.
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * The name displayed to user.
     *
     * @return string
     */
    public function getPresentation();

    /**
     * Set presentation.
     *
     * @param string $presentation
     */
    public function setPresentation($presentation);

    /**
     * Returns all option values.
     *
     * @return OptionValueInterface[]
     */
    public function getValues();

    /**
     * Sets all option values.
     *
     * @param OptionValueInterface[] $optionValues
     */
    public function setValues($optionValues);

    /**
     * Adds option value.
     *
     * @param OptionValueInterface $optionValue
     */
    public function addValue(OptionValueInterface $optionValue);

    /**
     * Removes option value.
     *
     * @param OptionValueInterface $optionValue
     */
    public function removeValue(OptionValueInterface $optionValue);

    /**
     * Checks whether option has given value.
     *
     * @param OptionValueInterface $optionValue
     *
     * @return Boolean
     */
    public function hasValue(OptionValueInterface $optionValue);
}
