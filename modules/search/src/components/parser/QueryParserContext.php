<?php

namespace im\search\components\parser;


class QueryParserContext
{
    private $_nextEntryField = null;

    private $_entries = [];

    function __construct($nextEntryField = null)
    {
        $this->_nextEntryField = $nextEntryField;
    }

    public function getNextEntryField()
    {
        return $this->_nextEntryField;
    }


    public function setNextEntryField($nextEntryField)
    {
        $this->_nextEntryField = $nextEntryField;
    }

    public function addEntry(ConditionEntry $entry)
    {
        $this->_entries[] = $entry;
        $this->_nextEntryField = null;
    }

    public function addOperator($operator)
    {
        $this->_entries[] = $operator;
    }

    public function addLogicalOperator($operator)
    {
        $this->_entries[] = $operator;
    }

    public function getCondition()
    {
        return new Condition($this->_entries);
    }
}