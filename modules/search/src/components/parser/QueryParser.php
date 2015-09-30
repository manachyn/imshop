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

        $addTermEntryAction             = new FSMAction($this, 'addTermEntry');
        $addPhraseEntryAction           = new FSMAction($this, 'addPhraseEntry');
        $setFieldAction                 = new FSMAction($this, 'setField');
        $setSignAction                  = new FSMAction($this, 'setSign');
        $setFuzzyProxAction             = new FSMAction($this, 'processFuzzyProximityModifier');
        $processModifierParameterAction = new FSMAction($this, 'processModifierParameter');
        $subConditionStartAction            = new FSMAction($this, 'subConditionStart');
        $subConditionEndAction              = new FSMAction($this, 'subConditionEnd');
        $logicalOperatorAction          = new FSMAction($this, 'logicalOperator');
        $operatorAction          = new FSMAction($this, 'operator');
        $includedRangeFirstTermAction        = new FSMAction($this, 'includedRangeFirstTerm');
        $includedRangeLastTermAction         = new FSMAction($this, 'includedRangeLastTerm');
        $closedRQFirstTermAction        = new FSMAction($this, 'closedRQFirstTerm');
        $closedRQLastTermAction         = new FSMAction($this, 'closedRQLastTerm');

        $fsm->addInputAction($fsm::STATE_QUERY_ELEMENT, QueryToken::TYPE_WORD, $addTermEntryAction);
        $fsm->addInputAction($fsm::STATE_QUERY_ELEMENT, QueryToken::TYPE_OPERATOR, $operatorAction);
        $fsm->addInputAction($fsm::STATE_QUERY_ELEMENT, QueryToken::TYPE_AND_OPERATOR,      $logicalOperatorAction);
        $fsm->addInputAction($fsm::STATE_QUERY_ELEMENT, QueryToken::TYPE_OR_OPERATOR,       $logicalOperatorAction);
        $fsm->addInputAction($fsm::STATE_QUERY_ELEMENT, QueryToken::TYPE_NOT_OPERATOR,      $logicalOperatorAction);
        $fsm->addInputAction($fsm::STATE_QUERY_ELEMENT, QueryToken::TYPE_LEFT_PARENTHESIS,  $subConditionStartAction);
        $fsm->addInputAction($fsm::STATE_QUERY_ELEMENT, QueryToken::TYPE_RIGHT_PARENTHESIS,    $subConditionEndAction);
        $fsm->addEntryAction($fsm::STATE_INCLUDED_RANGE_FIRST_TERM, $includedRangeFirstTermAction);
        $fsm->addEntryAction($fsm::STATE_INCLUDED_RANGE_LAST_TERM,  $includedRangeLastTermAction);
        //$fsm->addEntryAction($fsm::ST_CLOSEDINT_RQ_FIRST_TERM, $closedRQFirstTermAction);
        //$fsm->addEntryAction($fsm::ST_CLOSEDINT_RQ_LAST_TERM,  $closedRQLastTermAction);


        foreach ($tokens as $token) {
            $this->_currentToken = $token;
            $fsm->process($token->type);
        }

        return $this->_context->getCondition();
    }

    public function getFSM()
    {
        return new QueryParserFSM();
    }

    public function addTermEntry()
    {
        $entry = new Term($this->_currentToken->text, $this->_context->getNextEntryField());
        $this->_context->addEntry($entry);
    }

    public function operator()
    {
        $this->_context->addLogicalOperator($this->_currentToken->type);
    }

    public function logicalOperator()
    {
        $this->_context->addLogicalOperator($this->_currentToken->type);
    }


    public function subConditionStart()
    {
        $this->_contextStack[] = $this->_context;
        $this->_context = new QueryParserContext($this->_context->getNextEntryField());
    }


    public function subConditionEnd()
    {
        if (count($this->_contextStack) == 0) {
            throw new QueryParserException('Syntax Error: mismatched parentheses, every opening must have closing. Char position ' . $this->_currentToken->position . '.' );
        }
        $condition = $this->_context->getCondition();
        $this->_context = array_pop($this->_contextStack);
        $this->_context->addEntry(new SubCondition($condition));
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

        $rangeCondition = new Range($from, $to, true, true);
        $entry      = new SubCondition($rangeCondition);
        $this->_context->addEntry($entry);
    }
}