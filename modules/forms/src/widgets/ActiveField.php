<?php

namespace im\forms\widgets;

/**
 * Class ActiveField.
 * Extended ActiveField widget. Allows to set name prefix and tabular index.
 * @package im\forms\widgets
 */
class ActiveField extends \yii\widgets\ActiveField
{
    /**
     * @var string name prefix
     */
    public $namePrefix;

    /**
     * @var string tabular index
     */
    public $tabularIndex;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if ($this->namePrefix) {
            $this->inputOptions['name'] = $this->namePrefix;
            if (isset($this->tabularIndex)) {
                $this->inputOptions['name'] .= "[$this->tabularIndex]" . "[$this->attribute]";
            } else {
                $this->inputOptions['name'] .= $this->attribute;
            }
        }

        if (isset($this->tabularIndex)) {
            $this->attribute = "[$this->tabularIndex]" . $this->attribute;
        }
    }

    /**
     * @inheritdoc
     */
    public function checkbox($options = [], $enclosedByLabel = true)
    {
        if (isset($this->inputOptions['name'])) {
            $options['name'] = $this->inputOptions['name'];
        }

        return parent::checkbox($options, $enclosedByLabel);
    }
} 