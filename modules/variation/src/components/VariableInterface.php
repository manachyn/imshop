<?php

namespace im\variation\components;

/**
 * Should be implemented by models that support variants and options.
 *
 */
interface VariableInterface
{
    /**
     * Returns master variant.
     *
     * @return VariantInterface
     */
    public function getMasterVariant();

    /**
     * Sets master variant.
     *
     * @param VariantInterface $variant
     */
    public function setMasterVariant(VariantInterface $variant);

    /**
     * Has any variants?
     * This method is not for checking if entity has any variations.
     * It should determine if any variants (other than internal master) exist.
     *
     * @return Boolean
     */
    public function hasVariants();

    /**
     * Returns all entity variants.
     * This collection should exclude the master variant.
     *
     * @return VariantInterface[]
     */
    public function getVariants();

    /**
     * Sets all entity variants.
     *
     * @param VariantInterface[] $variants
     */
    public function setVariants($variants);

    /**
     * Adds variant.
     *
     * @param VariantInterface $variant
     */
    public function addVariant(VariantInterface $variant);

    /**
     * Removes variant from entity.
     *
     * @param VariantInterface $variant
     */
    public function removeVariant(VariantInterface $variant);

    /**
     * Checks whether entity has given variant.
     *
     * @param VariantInterface $variant
     *
     * @return Boolean
     */
    public function hasVariant(VariantInterface $variant);

    /**
     * This should return true only when entity has options.
     *
     * @return Boolean
     */
    public function hasOptions();

    /**
     * Returns all entity options.
     *
     * @return OptionInterface[]
     */
    public function getOptions();

    /**
     * Sets all entity options.
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
     * Removes option from product.
     *
     * @param OptionInterface $option
     */
    public function removeOption(OptionInterface $option);

    /**
     * Checks whether entity has given option.
     *
     * @param OptionInterface $option
     *
     * @return Boolean
     */
    public function hasOption(OptionInterface $option);
}
