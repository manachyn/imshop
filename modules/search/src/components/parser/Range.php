<?php

namespace im\search\components\parser;

class Range implements ConditionOperandInterface
{
    private $_field;

    private $_from;

    private $_to;

    private $_fromInclude;

    private $_toInclude;

    function __construct($field, $from, $to, $fromInclude, $toInclude)
    {
        $this->_field = $field;
        $this->_from = $from;
        $this->_to = $to;
        $this->_fromInclude = $fromInclude;
        $this->_toInclude = $toInclude;
    }
}