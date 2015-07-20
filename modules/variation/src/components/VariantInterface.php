<?php

namespace im\variation\components;

/**
 * Entity variant interface.
 *
 */
interface VariantInterface
{
    /**
     * Checks whether variant is master.
     *
     * @return Boolean
     */
    public function isMaster();

    /**
     * Defines whether variant is master.
     *
     * @param Boolean $master
     */
    public function setMaster($master);

    /**
     * Get presentation.
     *
     * This should be generated from option values
     * when no other is set.
     *
     * @return string
     */
    public function getPresentation();

    /**
     * Set custom presentation.
     *
     * @param string $presentation
     */
    public function setPresentation($presentation);

    /**
     * Get entity.
     *
     * @return VariableInterface
     */
    public function getEntity();

    /**
     * Set entity.
     *
     * @param VariableInterface|null $entity
     */
    public function setEntity(VariableInterface $entity = null);

    /**
     * Returns all option values.
     *
     * @return OptionValueInterface[]
     */
    public function getOptions();

    /**
     * Sets all variant options.
     *
     * @param OptionValueInterface[] $options
     */
    public function setOptions($options);

    /**
     * Adds option value.
     *
     * @param OptionValueInterface $option
     */
    public function addOption(OptionValueInterface $option);

    /**
     * Removes option from variant.
     *
     * @param OptionValueInterface $option
     */
    public function removeOption(OptionValueInterface $option);

    /**
     * Checks whether variant has given option.
     *
     * @param OptionValueInterface $option
     *
     * @return Boolean
     */
    public function hasOption(OptionValueInterface $option);

    /**
     * This method is used by product variants to inherit values
     * from a master variant, which is treated as a "template" for them.
     *
     * This is usable only when product has options.
     *
     * @param VariantInterface $masterVariant
     */
    public function setDefaults(VariantInterface $masterVariant);
}
