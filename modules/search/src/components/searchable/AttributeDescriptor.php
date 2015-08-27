<?php

namespace im\search\components\searchable;

use yii\base\Object;

class AttributeDescriptor extends Object
{
    /**
     * @var string
     */
    public $entity_type;

    /**
     * @var integer
     */
    public $attribute_id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $label;

    /**
     * @var mixed
     */
    public $value;
}