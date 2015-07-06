<?php

namespace im\forms\components;

use yii\db\ActiveRecord;
use yii\widgets\ActiveField;

class FieldSetOld implements FieldSetItemInterface, SetInterface
{
    /**
     * @event Event an event that is triggered after the item is added to set.
     */
    const EVENT_AFTER_ITEM_ADD = 'afterItemAdd';

    const EVENT_BEFORE_RENDER = 'beforeRender';

    const EVENT_BEFORE_ITEM_RENDER = 'beforeItemRender';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var ActiveRecord
     */
    protected $model;

    /**
     * @var FieldSetItemInterface
     */
    protected $parent;

    /**
     * @param $name
     * @param array $items
     * @param $model
     */
    function __construct($name, $items = [], $model = null)
    {
        $this->name = $name;
        $this->model = $model;
        $this->setItems($items);
    }

    /**
     * @inheritdoc
     */
    public function setItems($items)
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }
    }

    /**
     * @inheritdoc
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param ActiveRecord $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @return ActiveRecord
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param FieldSet $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return FieldSet
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param string $name
     * @return ActiveField|SetItemInterface
     */
    public function getItem($name) {
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
     * @param ActiveField|Tab|TabSet $item
     * @param string $parent
     * @return FieldSet
     */
    public function addItem($item, $parent = '')
    {
        if ($parent) {
            $parent = $this->getItem($parent);
            $parent->addItem($item);
        } else {
            if ($item instanceof FieldSet) {
                $item->setParent($this);
            }
            if (!$this->getModel() && ($itemModel = $this->getItemModel($item))) {
                $this->setModel($itemModel);
            }
            $this->items[$this->getItemName($item)] = $item;
            if ($model = $this->getModel()) {
                $model->trigger(self::EVENT_AFTER_ITEM_ADD, new FieldSetEvent(['item' => $item]));
            }
        }
        return $this;
    }

    /**
     * @param string $name
     * @param string $parent
     * @return FieldSet
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
                unset($this->items[$name]);
            }
        }
        return $this;
    }

    /**
     * @param ActiveField|Tab|TabSet $item
     * @param string $before
     * @param string $parent
     * @return FieldSet
     */
    public function addItemBefore($item, $before, $parent = '')
    {
        if ($parent) {
            $parent = $this->getItem($parent);
            $parent->addItemBefore($item, $before);
        } else {
            if (is_string($before)) {
                $before = $this->getItem($before);
            }
            $offset = array_search($this->getItemName($before), array_keys($this->items));
            $beforeItems = array_slice($this->items, 0, $offset);
            $afterItems = array_slice($this->items, $offset);
            $this->items = $beforeItems;
            $this->items[$this->getItemName($item)] = $item;
            $this->items += $afterItems;
            if ($model = $this->getModel()) {
                $model->trigger(self::EVENT_AFTER_ITEM_ADD, new FieldSetEvent(['item' => $item]));
            }
        }
        return $this;
    }

    /**
     * @param ActiveField|Tab|TabSet $item
     * @param string $after
     * @param string $parent
     * @return FieldSet
     */
    public function addItemAfter($item, $after, $parent = '')
    {
        if ($parent) {
            $parent = $this->getItem($parent);
            $parent->addItemAfter($item, $after);
        } else {
            if (is_string($after)) {
                $after = $this->getItem($after);
            }
            $offset = array_search($this->getItemName($after), array_keys($this->items));
            $beforeItems = array_slice($this->items, 0, $offset + 1);
            $afterItems = array_slice($this->items, $offset + 1);
            $this->items = $beforeItems;
            $this->items[$this->getItemName($item)] = $item;
            $this->items += $afterItems;
            if ($model = $this->getModel()) {
                $model->trigger(self::EVENT_AFTER_ITEM_ADD, new FieldSetEvent(['item' => $item]));
            }
        }
        return $this;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function hasItem($name)
    {
        return array_key_exists($name, $this->items);
    }

    public function renderFieldSet($tabbed = true) {
        if ($model = $this->getModel()) {
            $model->trigger(self::EVENT_BEFORE_RENDER, new FieldSetEvent(['fieldSet' => $this]));
        }
        $this->render($tabbed);
    }

    /**
     * Renders field set
     * @param bool $tabbed
     * @return string
     */
    public function render($tabbed = true) {
        $output = '';
        foreach ($this->items as $item) {
            if ($item instanceof ActiveField) {
                $output .= $item;
            } else {
                /** @var FieldSet $item */
                $output .= $item->render($tabbed);
            }
        }

        return $output;
    }

    /**
     * @param ActiveField|TabSet|Tab $item
     * @return string name
     * @throws \Exception
     */
    protected function getItemName($item)
    {
        if ($item instanceof ActiveField) {
            $name = $item->attribute;
        } elseif ($item instanceof Tab) {
            $name = $item->getName();
        } elseif ($item instanceof TabSet) {
            $name = $item->getName();
        } else {
            throw new \Exception('Unsupported filed type');
        }

        return $name;
    }

    /**
     * @param ActiveField|SetItemInterface $item
     * @return ActiveRecord|null
     * @throws \Exception
     */
    protected function getItemModel($item)
    {
        if ($item instanceof ActiveField) {
            $model = $item->model;
        } elseif ($item instanceof FieldSet) {
            $model = $item->getModel();
        } else {
            throw new \Exception('Unsupported filed type');
        }

        return $model;
    }
}