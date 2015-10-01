<?php

namespace im\search\components\parser;

interface ConditionInterface
{
    /**
     * @return Operator
     */
    public function getOperator();

    /**
     * @param ConditionOperandInterface $operand
     */
    public function addOperand($operand);

    /**
     * @param ConditionOperandInterface[] $operands
     */
    public function setOperands($operands);

    /**
     * @return ConditionOperandInterface[]
     */
    public function getOperands();
}