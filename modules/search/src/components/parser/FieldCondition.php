<?php

namespace im\search\components\parser;

class FieldCondition extends Condition implements ConditionOperandInterface
{
    private $_field;

    function __construct($field, Operator $operator = null, $operands)
    {
        parent::__construct($operator, $operands);
        $this->_field = $field;
    }


}