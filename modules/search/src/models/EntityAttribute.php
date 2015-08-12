<?php

namespace im\search\models;

use yii\base\Object;

class EntityAttribute extends Object
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
}