<?php

namespace im\search\components\parser;


class QueryParserContext
{
    private $_field = null;

    private $_operator = null;

    /**
     * @var ConditionInterface
     */
    private $_condition;

    private $_entries = [];

    function __construct($field = null, $operator = null)
    {
        $this->_field = $field;
        $this->_operator = $operator;
        $this->_condition = new Condition(new LogicalOperator(LogicalOperator::TYPE_AND));
    }

    public function getField()
    {
        return $this->_field;
    }

    public function setField($field)
    {
        $this->_field = $field;
    }

    /**
     * @return null
     */
    public function getOperator()
    {
        return $this->_operator;
    }

    /**
     * @param null $operator
     */
    public function setOperator($operator)
    {
        $this->_operator = $operator;
//        $operands = $this->_condition->getOperands();
//        array_pop($operands);
//        $this->_condition->setOperands($operands);
        array_pop($this->_entries);
    }

    public function addOperand(ConditionOperandInterface $operand)
    {
//        $this->_condition->addOperand($operand);
        $this->_entries[] = $operand;
    }

    public function setOperands($operands)
    {
//        $this->_condition->setOperands($operands);
    }

    public function setLogicalOperator($operator)
    {
        $this->_entries[] = $operator;
    }

    /**
     * @return ConditionInterface
     */
    public function getCondition()
    {
        return $this->_condition;
    }

    public function getEntries()
    {
        return $this->_entries;
    }
}