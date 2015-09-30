<?php

namespace im\search\components\parser;

class Range extends Condition
{
    private $_from;

    private $_to;

    private $_fromInclude;

    private $_toInclude;

    function __construct($from, $to, $fromInclude, $toInclude)
    {
        $this->_from = $from;
        $this->_to = $to;
        $this->_fromInclude = $fromInclude;
        $this->_toInclude = $toInclude;
    }


}