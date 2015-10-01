<?php

namespace im\search\components\parser;

class Term implements ConditionOperandInterface
{
    private $_field;

    private $_term;

    function __construct($field, $term)
    {
        $this->_field = $field;
        $this->_term = $term;
    }
}