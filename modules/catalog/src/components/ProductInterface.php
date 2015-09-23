<?php

namespace im\catalog\components;

use im\variation\components\VariableInterface;

/**
 * Product interface.
 *
 * @package im\catalog\components
 */
interface ProductInterface extends ProductTypeSubjectInterface, VariableInterface
{
    /**
     * Get id.
     *
     * @return integer
     */
    public function getId();

    /**
     * Set sku.
     *
     * @param string $sku
     */
    public function setSku($sku);

    /**
     * Get sku.
     *
     * @return string
     */
    public function getSku();

    /**
     * Set title.
     *
     * @param string $title
     */
    public function setTitle($title);

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Set slug.
     *
     * @param string $slug
     */
    public function setSlug($slug);

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug();

    /**
     * Set description.
     *
     * @param string $description
     */
    public function setDescription($description);

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription();

    /**
     * Set short description.
     *
     * @param string $description
     */
    public function setShortDescription($description);

    /**
     * Get short description.
     *
     * @return string
     */
    public function getShortDescription();

    /**
     * Check whether the product is available.
     *
     * @return bool
     */
    public function isAvailable();
} 