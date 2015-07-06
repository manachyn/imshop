<?php

namespace im\forms\components;

/**
 * Interface SetInterface base interface for set of items.
 *
 * @package im\forms\components
 */
interface SetInterface
{
    /**
     * Return all items of set.
     *
     * @return array
     */
    public function getItems();

    /**
     * Set items.
     *
     * @param array $items
     */
    public function setItems($items);

    /**
     * Return item by name.
     *
     * @param string $name
     * @return mixed
     */
    public function getItem($name);

    /**
     * Add item to the set.
     *
     * @param SetItemInterface
     * @param string $parent
     * @return SetInterface
     */
    public function addItem($item, $parent = '');

    /**
     * Add item before specific one.
     *
     * @param SetItemInterface
     * @param string $before
     * @param string $parent
     * @return SetInterface
     */
    public function addItemBefore($item, $before, $parent = '');

    /**
     * Add item after specific one.
     *
     * @param SetItemInterface
     * @param string $after
     * @param string $parent
     * @return SetInterface
     */
    public function addItemAfter($item, $after, $parent = '');

    /**
     * Remove item.
     *
     * @param string $name
     * @param string $parent
     * @return SetInterface
     */
    public function removeItem($name, $parent = '');

    /**
     * Whether the set contains item with specific name.
     *
     * @param string $name
     * @return boolean
     */
    public function hasItem($name);

    /**
     * Render set.
     *
     * @param array $params
     * @return string
     */
    public function renderSet($params = []);
}