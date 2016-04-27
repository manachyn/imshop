<?php

namespace im\search\components\searchable;

use yii\base\Object;

/**
 * Class AttributeDescriptor
 * @package im\search\components\searchable
 */
class AttributeDescriptor extends Object
{
    const TYPE_STRING = 'string';
    const TYPE_INTEGER = 'int';
    const TYPE_FLOAT = 'float';
    const TYPE_BOOLEAN = 'bool';

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

    /**
     * @var string
     */
    public $type;

    /**
     * @var AttributeDescriptorDependency
     */
    public $dependency;

    /**
     * @var array
     */
    public $params = [];
}