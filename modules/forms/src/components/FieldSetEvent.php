<?php

namespace im\forms\components;

use yii\base\ModelEvent;
use yii\widgets\ActiveField;

/**
 * Class FieldSetEvent
 * @package im\forms\components
 */
class FieldSetEvent extends ModelEvent
{
    /**
     * @var FieldSet
     */
    public $fieldSet;

    /**
     * @var ActiveField|Tab|TabSet
     */
    public $item;
} 