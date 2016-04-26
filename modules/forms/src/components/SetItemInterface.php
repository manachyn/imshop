<?php

namespace im\forms\components;

/**
 * Interface SetItemInterface base interface fom set item.
 *
 * @package im\forms\components
 */
interface SetItemInterface
{
    /**
     * Return item's name.
     *
     * @return string
     */
    public function getName();

    /**
     * Set item's name.
     *
     * @param string $name
     */
    public function setName($name);

    /**
     * Return item's parent.
     *
     * @return SetInterface
     */
    public function getParent();

    /**
     * Set item's parent.
     *
     * @param SetInterface $parent
     */
    public function setParent($parent);

    /**
     * Render item.
     *
     * @param array $params
     * @return string
     */
    public function render($params = []);
} 