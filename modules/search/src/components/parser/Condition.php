<?php

namespace im\search\components\parser;

class Condition
{
    private $_entries;

    function __construct($entries)
    {
        $this->_entries = $entries;
    }
}