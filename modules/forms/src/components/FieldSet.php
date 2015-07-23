<?php

namespace im\forms\components;

use yii\base\Model;
use yii\base\Widget;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;
use yii\widgets\InputWidget;

/**
 * Class FieldSet the set for form fields.
 * Allows to create form as set of fields, dynamically change form or separate form filed
 * using set events from other components.
 *
 * @package im\forms\components
 */
class FieldSet extends Set
{
    /**
     * @event Event an event that is triggered after the item is added to set.
     */
    const EVENT_AFTER_ITEM_ADD = 'afterItemAdd';

    /**
     * @event Event an event that is triggered before the set is rendered.
     */
    const EVENT_BEFORE_RENDER = 'beforeRender';

    /**
     * @event Event an event that is triggered before the item is rendered.
     */
    const EVENT_BEFORE_ITEM_RENDER = 'beforeItemRender';

    /**
     * @var ActiveForm form instance
     */
    protected $form;

    /**
     * @var Model model instance
     */
    protected $model;

    /**
     * @var FieldSet for nested sets
     */
    protected $parent;

    /**
     * @param string $name
     * @param array $items
     * @param ActiveForm $form
     * @param Model $model
     */
    function __construct($name, $items = [], $form = null, $model = null)
    {
        $this->form = $form;
        $this->model = $model;
        parent::__construct($name, $items);
    }

    /**
     * Set form instance.
     *
     * @param $form ActiveForm
     */
    public function setForm($form)
    {
        $this->form = $form;
    }

    /**
     * Return form instance.
     *
     * @return ActiveForm
     */
    public function getForm()
    {
        if ($this->form) {
            return $this->form;
        } elseif ($this->parent) {
            return $this->parent->getForm();
        }

        return null;
    }

    /**
     * Set model instance.
     *
     * @param $model Model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * Return model instance.
     *
     * @return Model
     */
    public function getModel()
    {
        if ($this->model) {
            return $this->model;
        } elseif ($this->parent) {
            return $this->parent->getModel();
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function beforeItemAdd($item)
    {
        if (!$this->model && ($itemModel = $this->getItemModel($item))) {
            $this->setModel($itemModel);
        }

        if (!$this->form && ($itemForm = $this->getItemForm($item))) {
            $this->setForm($itemForm);
        }
    }

    /**
     * @inheritdoc
     */
    public function afterItemAdd($item)
    {
        if ($model = $this->getModel()) {
            $model->trigger(self::EVENT_AFTER_ITEM_ADD, new FieldSetEvent(['fieldSet' => $this, 'item' => $item]));
        }
    }

    /**
     * @inheritdoc
     */
    public function beforeRender()
    {
        if ($model = $this->getModel()) {
            $event = new FieldSetEvent(['fieldSet' => $this]);
            $model->trigger(self::EVENT_BEFORE_RENDER, $event);
            return $event->isValid;
        }

        return parent::beforeRender();
    }

    /**
     * @inheritdoc
     */
    public function beforeItemRender($item)
    {
        if ($model = $this->getModel()) {
            $event = new FieldSetEvent(['fieldSet' => $this, 'item' => $item]);
            $model->trigger(self::EVENT_BEFORE_ITEM_RENDER, $event);
            return $event->isValid;
        }

        return parent::beforeItemRender($item);
    }

    /**
     * @inheritdoc
     */
    public function render($params = [])
    {
        $output = '';

        foreach ($this->items as $item) {
            if (!$this->beforeItemRender($item)) {
                continue;
            }
            if ($item instanceof ActiveField) {
                $output .= $item;
            } elseif ($item instanceof Widget) {
                $output .= $item->run();
            } else {
                /** @var SetItemInterface $item */
                $output .= $item->render($params);
            }
        }

        return $output;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->renderSet();
    }

    /**
     * @param ActiveField|SetItemInterface $item
     * @return string name
     * @throws \Exception
     */
    protected function getItemName($item)
    {
        if ($item instanceof ActiveField || $item instanceof InputWidget) {
            $name = $item->attribute;
        } elseif ($item instanceof SetItemInterface) {
            $name = $item->getName();
        } else {
            throw new \Exception('Unsupported item type');
        }

        return $name;
    }

    /**
     * @param ActiveField|SetItemInterface $item
     * @return Model|null
     */
    protected function getItemModel($item)
    {
        if ($item instanceof ActiveField) {
            return $item->model;
        } elseif ($item instanceof FieldSet) {
            return $item->getModel();
        }

        return null;
    }

    /**
     * @param ActiveField|SetItemInterface $item
     * @return ActiveForm|null
     * @throws \Exception
     */
    protected function getItemForm($item)
    {
        if ($item instanceof ActiveField) {
            return $item->form;
        } elseif ($item instanceof FieldSet) {
            return $item->getForm();
        }

        return null;
    }
}