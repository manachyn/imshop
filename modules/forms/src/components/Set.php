<?php

namespace im\forms\components;

/**
 * Class Set implement base methods of set and set item.
 *
 * @package im\forms\components
 */
class Set implements SetInterface, SetItemInterface
{
    /**
     * @var string set name
     */
    protected $name;

    /**
     * @var SetInterface set parent
     */
    protected $parent;

    /**
     * @var array set items
     */
    protected $items = [];

    /**
     * @param string $name
     * @param array $items
     */
    function __construct($name, $items = [])
    {
        $this->name = $name;
        $this->setItems($items);
    }

    /**
     * @inheritdoc
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @inheritdoc
     */
    public function setItems($items)
    {
        foreach ($items as $item) {
            if ($item) {
                $this->addItem($item);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getItem($name)
    {
        if (strpos($name, '.') !== false) {
            list($name, $end) = explode('.', $name, 2);
        } else {
            $end = null;
        }

        foreach($this->items as $key => $item) {
            if($key == $name) {
                if($end) {
                    if($item instanceof SetInterface) {
                        return $item->getItem($end);
                    } else {
                        return null;
                    }
                } else {
                    return $item;
                }
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function addItem($item, $parent = '')
    {
        if ($parent) {
            $parent = $this->getItem($parent);
            $parent->addItem($item);
        } else {
            if ($item instanceof SetItemInterface) {
                $item->setParent($this);
            }
            $this->beforeItemAdd($item);
            $this->items[$this->getItemName($item)] = $item;
            $this->afterItemAdd($item);
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addItemBefore($item, $before, $parent = '')
    {
        if ($parent) {
            $parent = $this->getItem($parent);
            $parent->addItemBefore($item, $before);
        } else {
            if ($item instanceof SetItemInterface) {
                $item->setParent($this);
            }
            $this->beforeItemAdd($item);
            $before = $this->getItem($before);
            $offset = array_search($this->getItemName($before), array_keys($this->items));
            $beforeItems = array_slice($this->items, 0, $offset);
            $afterItems = array_slice($this->items, $offset);
            $this->items = $beforeItems;
            $this->items[$this->getItemName($item)] = $item;
            $this->items += $afterItems;
            $this->afterItemAdd($item);
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function addItemAfter($item, $after, $parent = '')
    {
        if ($parent) {
            $parent = $this->getItem($parent);
            $parent->addItemAfter($item, $after);
        } else {
            if ($item instanceof SetItemInterface) {
                $item->setParent($this);
            }
            $this->beforeItemAdd($item);
            $after = $this->getItem($after);
            $offset = array_search($this->getItemName($after), array_keys($this->items));
            $beforeItems = array_slice($this->items, 0, $offset + 1);
            $afterItems = array_slice($this->items, $offset + 1);
            $this->items = $beforeItems;
            $this->items[$this->getItemName($item)] = $item;
            $this->items += $afterItems;
            $this->afterItemAdd($item);
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function removeItem($name, $parent = '')
    {
        if ($parent) {
            $parent = $this->getItem($parent);
            $parent->removeItem($name);
        } else {
            if (strpos($name, '.') !== false) {
                $parts = explode('.', $name);
                $this->removeItem(array_pop($parts), implode('.', $parts));
            } elseif ($this->hasItem($name)) {
                $item = $this->items[$name];
                if ($item instanceof SetItemInterface) {
                    $item->setParent($this);
                }
                unset($this->items[$name]);
            }
        }
        return $this;
    }

    /**
     * @inheritdoc
     */
    public function hasItem($name)
    {
        return isset($this->items[$name]);
    }

    /**
     * @inheritdoc
     */
    public function renderSet($params = [])
    {
        if (!$this->beforeRender()) {
            return '';
        }

        $params['tabbed'] = isset($params['tabbed']) ? $params['tabbed'] : true;

        return $this->render($params);
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritdoc
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
        $this->parent;
    }

    /**
     * @inheritdoc
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @inheritdoc
     */
    public function render($params = [])
    {
        $output = '';

        foreach ($this->items as $item) {
            /** @var $item SetItemInterface */
            if (!$this->beforeItemRender($item)) {
                continue;
            }
            $output .= $item->render($params);
        }

        return $output;
    }

    /**
     * This method is invoked before adding a item to the set.
     * You may override this method to do processing before the item is added.
     *
     * @param SetItemInterface $item
     */
    public function beforeItemAdd($item)
    {
    }

    /**
     * This method is invoked after adding a item to the set.
     * You may override this method to do postprocessing after the item is added.
     *
     * @param SetItemInterface $item
     */
    public function afterItemAdd($item)
    {
    }

    /**
     * This method is invoked before rendering a set.
     * You may override this method to do processing before the set is rendered.
     */
    public function beforeRender()
    {
        return true;
    }

    /**
     * This method is invoked before rendering a item.
     * You may override this method to do processing before the item is rendered.
     *
     * @param SetItemInterface $item
     * @return bool
     */
    public function beforeItemRender($item)
    {
        return true;
    }

    /**
     * Return string presentation of set
     * @return string
     */
    public function __toString()
    {
        return $this->renderSet();
    }

    /**
     * @param SetItemInterface $item
     * @return string name
     */
    protected function getItemName($item)
    {
        return $item->getName();
    }
}