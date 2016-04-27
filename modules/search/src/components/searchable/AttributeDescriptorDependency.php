<?php

namespace im\search\components\searchable;

use yii\base\Object;

/**
 * Class AttributeDescriptorDependency
 * @package im\search\components\searchable
 */
class AttributeDescriptorDependency extends Object
{
    /**
     * @var string
     */
    public $class;

    /**
     * @var string
     */
    public $attribute;
}