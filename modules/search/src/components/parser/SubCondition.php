<?php

namespace im\search\components\parser;

class SubCondition extends ConditionEntry
{
    /**
     * @var Condition
     */
    private $_condition;

    function __construct(Condition $condition)
    {
        $this->_condition = $condition;
    }


}