<?php

namespace im\search\components\parser;

use im\fsm\FSMAction;
use Symfony\Component\ExpressionLanguage\TokenStream;

class QueryParser
{
    private $_fsm;

    /**
     * Current query parser context
     *
     * @var QueryParserContext
     */
    private $_context;

    /**
     * Context stack
     *
     * @var array
     */
    private $_contextStack;

    /**
     * Current token
     *
     * @var QueryToken
     */
    private $_currentToken;

    /**
     * Current token
     *
     * @var QueryToken
     */
    private $_nextToken;

    /**
     * Last token
     *
     * It can be processed within FSM states, but this addirional state simplifies FSM
     * @var QueryToken
     */
    private $_lastToken = null;

    /**
     * Range query first term
     *
     * @var string
     */
    private $_rangeFirstTerm = null;

    /**
     * @param QueryToken[] $tokens
     * @return array
     */
    public function parse($tokens)
    {
        $fsm = $this->getFSM();
        $this->_context = new QueryParserContext();
        $this->_contextStack = [];

        $addTermEntryAction = new FSMAction($this, 'addTermEntry');
        $operatorAction = new FSMAction($this, 'operator');
        $logicalOperatorAction = new FSMAction($this, 'logicalOperator');
        $conditionStartAction = new FSMAction($this, 'conditionStart');
        $conditionEndAction = new FSMAction($this, 'conditionEnd');
        $includedRangeFirstTermAction = new FSMAction($this, 'includedRangeFirstTerm');
        $includedRangeLastTermAction = new FSMAction($this, 'includedRangeLastTerm');

        $fsm->addInputAction($fsm::STATE_QUERY_ELEMENT, QueryToken::TYPE_WORD, $addTermEntryAction);
        $fsm->addInputAction($fsm::STATE_QUERY_ELEMENT, QueryToken::TYPE_OPERATOR, $operatorAction);
        $fsm->addInputAction($fsm::STATE_QUERY_ELEMENT, QueryToken::TYPE_AND_OPERATOR, $logicalOperatorAction);
        $fsm->addInputAction($fsm::STATE_QUERY_ELEMENT, QueryToken::TYPE_OR_OPERATOR, $logicalOperatorAction);
        $fsm->addInputAction($fsm::STATE_QUERY_ELEMENT, QueryToken::TYPE_NOT_OPERATOR, $logicalOperatorAction);
        $fsm->addInputAction($fsm::STATE_QUERY_ELEMENT, QueryToken::TYPE_LEFT_PARENTHESIS, $conditionStartAction);
        $fsm->addInputAction($fsm::STATE_QUERY_ELEMENT, QueryToken::TYPE_RIGHT_PARENTHESIS, $conditionEndAction);
        $fsm->addEntryAction($fsm::STATE_INCLUDED_RANGE_FIRST_TERM, $includedRangeFirstTermAction);
        $fsm->addEntryAction($fsm::STATE_INCLUDED_RANGE_LAST_TERM, $includedRangeLastTermAction);


        foreach ($tokens as $key => $token) {
            $this->_currentToken = $token;
            if (isset($tokens[$key + 1])) {
                $this->_nextToken = $tokens[$key + 1];
            }
            $fsm->process($token->type);
            $this->_lastToken = $token;
        }

        return $this->_context->getCondition();
    }

    public function getFSM()
    {
        return new QueryParserFSM();
    }

    public function addTermEntry()
    {
//        if ($this->_lastToken && in_array($this->_lastToken->type, QueryToken::getLogicalOperatorsTypes())) {
//            /** @var LogicalOperator $operator */
//            $operator = null;
//            $operands = [];
//            $condition = null;
//            foreach ($this->_context->getEntries() as $entry) {
//                if ($entry instanceof LogicalOperator) {
//                    if (!$operator) {
//                        $operator = $entry;
//                    } elseif ($operator->getType() !== $entry->getType()) {
//                        $condition = new Condition($operator, $operands);
//                        $operands = [$condition];
//                        $operator = $entry;
//                    }
//                } else {
//                    $operands[] = $entry;
//                }
//            }
//            if ($condition) {
//                $this->_context->getCondition()->setOperands([$condition]);
//            }
//        }
        //$operand = new Term($this->_context->getField(), $this->_currentToken->text);
        $operand = new FieldCondition($this->_context->getField(), $this->_context->getOperator(), $this->_currentToken->text);
        $this->_context->addOperand($operand);
    }

    public function operator()
    {
        if (!$this->_lastToken || $this->_lastToken->type !== QueryToken::TYPE_WORD) {
            throw new QueryParserException('Syntax Error: operator must follow field.');
        }
        $this->_context->setField($this->_lastToken->text);
        $this->_context->setOperator(new Operator($this->_currentToken->text));
    }

    public function logicalOperator()
    {
        $operator = new LogicalOperator($this->_currentToken->type);
        $this->_context->setLogicalOperator($operator);
    }


    public function conditionStart()
    {
        $this->_contextStack[] = $this->_context;
        $this->_context = new QueryParserContext($this->_context->getField(), $this->_context->getOperator());
    }


    public function conditionEnd()
    {
        if (count($this->_contextStack) == 0) {
            throw new QueryParserException('Syntax Error: mismatched parentheses, every opening must have closing. Char position ' . $this->_currentToken->position . '.' );
        }
        $operands = $this->_context->getCondition()->getOperands();
        $this->_context = array_pop($this->_contextStack);
        $this->_context->addOperand(new Condition(new LogicalOperator('Undefined'), $operands));
    }

    public function includedRangeFirstTerm()
    {
        $this->_rangeFirstTerm = $this->_currentToken->text;
    }

    public function includedRangeLastTerm()
    {
        $from = $this->_rangeFirstTerm;
        $to = $this->_currentToken->text;
        if ($from === null  &&  $to === null) {
            throw new QueryParserException('At least one range query boundary term must be non-empty term');
        }

        $range = new Range($this->_context->getField(), $from, $to, true, true);
        //$entry      = new SubCondition($rangeCondition);
        $this->_context->addOperand($range);
    }
}