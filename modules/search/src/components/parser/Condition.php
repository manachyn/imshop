<?php

namespace im\search\components\parser;

class Condition implements ConditionInterface, ConditionOperandInterface
{
    /**
     * @var Operator
     */
    private $_operator;

    /**
     * @var ConditionOperandInterface[]
     */
    private $_operands;

    /**
     * @param Operator $operator
     * @param ConditionOperandInterface[] $operands
     */
    function __construct(Operator $operator = null, $operands = [])
    {
        $this->_operator = $operator;
        $this->_operands = $operands;
    }

    /**
     * @inheritdoc
     */
    public function getOperator()
    {
        return $this->_operator;
    }

    /**
     * @inheritdoc
     */
    public function addOperand($operand)
    {
        $this->_operands[] = $operand;
    }

    /**
     * @inheritdoc
     */
    public function setOperands($operands)
    {
        $this->_operands = $operands;
    }

    /**
     * @inheritdoc
     */
    public function getOperands()
    {
        return $this->_operands;
    }
}