<?php

namespace im\search\components\parser;

class Operator
{
    const TYPE_EQUAL = 'equal';
    const TYPE_GTE = 'gte';
    const TYPE_GT = 'gt';
    const TYPE_LTE = 'lte';
    const TYPE_LT = 'lt';

    /**
     * @var string
     */
    private $_type;

    function __construct($type)
    {
        $this->_type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->_type;
    }
}