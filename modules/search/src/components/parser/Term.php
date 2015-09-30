<?php

namespace im\search\components\parser;

class Term extends ConditionEntry
{
    private $_term;

    private $_field;

    function __construct($term, $field)
    {
        $this->_term = $term;
        $this->_field = $field;
    }


}