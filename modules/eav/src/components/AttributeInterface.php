<?php

namespace im\eav\components;

interface AttributeInterface
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
     * The type of the attribute.
     *
     * @return string
     */
    public function getType();

    /**
     * Set type of the attribute.
     *
     * @param string $type
     */
    public function setType($type);

    /**
     * Whether the attribute has predefined values.
     *
     * @return bool
     */
    public function isValuesPredefined();
} 