<?php

namespace im\search\components\query\parser;

use im\fsm\FSM;
use im\fsm\FSMAction;
use im\search\components\query\parser\entry\Condition;
use im\search\components\query\parser\entry\Phrase;
use im\search\components\query\parser\entry\QueryEntryInterface;
use im\search\components\query\parser\entry\SubQuery;
use im\search\components\query\Range;
use Yii;

/**
 * Finite State Machine based query parser.
 *
 * @package im\search\components\query\parser
 */
class QueryParserNew extends FSM implements QueryParserInterface
{
    /**
     * Boolean operators constants
     */
    const OPERATOR_OR = 0;
    const OPERATOR_AND = 1;

    /**
     * Query lexer is used to split query string into tokens.
     *
     * @var QueryLexerInterface
     */
    public $lexer = 'im\search\components\query\parser\QueryLexer';

    /**
     * Default boolean queries operator
     *
     * @var int
     */
    public $defaultOperator = self::OPERATOR_OR;

    /**
     * Boolean operator which has higher priority
     *
     * @var int
     */
    public $higherPriorityOperator = self::OPERATOR_AND;

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
     * Parsing context (config)
     *
     * @var QueryParserContextInterface
     */
    private $_parsingContext;

    /**
     * Current token
     *
     * @var QueryToken
     */
    private $_currentToken;

    /**
     * Last token
     *
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
     * Whether to include range first term
     *
     * @var bool
     */
    private $_includeRangeFirstTerm = true;

    /**
     * Query parser State Machine states
     */
    const STATE_QUERY_ELEMENT = 0;
    const STATE_INCLUDED_RANGE_START = 1;
    const STATE_INCLUDED_RANGE_FIRST_TERM = 2;
    const STATE_INCLUDED_RANGE_TO_TERM = 3;
    const STATE_INCLUDED_RANGE_LAST_TERM = 4;
    const STATE_INCLUDED_RANGE_END = 5;
    const STATE_EXCLUDED_RANGE_START = 6;
    const STATE_EXCLUDED_RANGE_FIRST_TERM = 7;
    const STATE_EXCLUDED_RANGE_TO_TERM = 8;
    const STATE_EXCLUDED_RANGE_LAST_TERM = 9;
    const STATE_EXCLUDED_RANGE_END = 10;
    const STATE_OPERATOR = 11;

    /**
     * Creates parser
     */
    public function __construct()
    {
        parent::__construct([
            self::STATE_QUERY_ELEMENT,
            self::STATE_INCLUDED_RANGE_START,
            self::STATE_INCLUDED_RANGE_FIRST_TERM,
            self::STATE_INCLUDED_RANGE_TO_TERM,
            self::STATE_INCLUDED_RANGE_LAST_TERM,
            self::STATE_INCLUDED_RANGE_END,
            self::STATE_EXCLUDED_RANGE_START,
            self::STATE_EXCLUDED_RANGE_FIRST_TERM,
            self::STATE_EXCLUDED_RANGE_TO_TERM,
            self::STATE_EXCLUDED_RANGE_LAST_TERM,
            self::STATE_EXCLUDED_RANGE_END,
        ], QueryToken::getTypes());

        $this->addTransitions([
            [self::STATE_QUERY_ELEMENT, QueryToken::TYPE_WORD, self::STATE_QUERY_ELEMENT],
            [self::STATE_QUERY_ELEMENT, QueryToken::TYPE_PHRASE, self::STATE_QUERY_ELEMENT],
            [self::STATE_QUERY_ELEMENT, QueryToken::TYPE_NUMBER, self::STATE_QUERY_ELEMENT],
            [self::STATE_QUERY_ELEMENT, QueryToken::TYPE_DATE, self::STATE_QUERY_ELEMENT],
            [self::STATE_QUERY_ELEMENT, QueryToken::TYPE_OPERATOR, self::STATE_QUERY_ELEMENT],
            [self::STATE_QUERY_ELEMENT, QueryToken::TYPE_AND_OPERATOR, self::STATE_QUERY_ELEMENT],
            [self::STATE_QUERY_ELEMENT, QueryToken::TYPE_OR_OPERATOR, self::STATE_QUERY_ELEMENT],
            [self::STATE_QUERY_ELEMENT, QueryToken::TYPE_NOT_OPERATOR, self::STATE_QUERY_ELEMENT],
            [self::STATE_QUERY_ELEMENT, QueryToken::TYPE_LEFT_SQUARE_BRACKET, self::STATE_INCLUDED_RANGE_START],
            [self::STATE_QUERY_ELEMENT, QueryToken::TYPE_LEFT_PARENTHESIS, self::STATE_EXCLUDED_RANGE_START],
            //[self::STATE_QUERY_ELEMENT, QueryToken::TYPE_LEFT_PARENTHESIS, self::STATE_QUERY_ELEMENT],
            //[self::STATE_QUERY_ELEMENT, QueryToken::TYPE_RIGHT_PARENTHESIS, self::STATE_QUERY_ELEMENT],
            //[self::STATE_QUERY_ELEMENT, QueryToken::TYPE_TO_LEXEME, self::STATE_EXCLUDED_RANGE_TO_TERM],
        ]);

        $this->addTransitions([
            [self::STATE_INCLUDED_RANGE_START, QueryToken::TYPE_WORD, self::STATE_INCLUDED_RANGE_FIRST_TERM],
            [self::STATE_INCLUDED_RANGE_START, QueryToken::TYPE_DATE, self::STATE_INCLUDED_RANGE_FIRST_TERM],
            [self::STATE_INCLUDED_RANGE_START, QueryToken::TYPE_NUMBER, self::STATE_INCLUDED_RANGE_FIRST_TERM],
            [self::STATE_INCLUDED_RANGE_FIRST_TERM, QueryToken::TYPE_TO_LEXEME, self::STATE_INCLUDED_RANGE_TO_TERM],
            [self::STATE_INCLUDED_RANGE_TO_TERM, QueryToken::TYPE_WORD, self::STATE_INCLUDED_RANGE_LAST_TERM],
            [self::STATE_INCLUDED_RANGE_TO_TERM, QueryToken::TYPE_DATE, self::STATE_INCLUDED_RANGE_LAST_TERM],
            [self::STATE_INCLUDED_RANGE_TO_TERM, QueryToken::TYPE_NUMBER, self::STATE_INCLUDED_RANGE_LAST_TERM],
            [self::STATE_INCLUDED_RANGE_LAST_TERM, QueryToken::TYPE_RIGHT_SQUARE_BRACKET, self::STATE_INCLUDED_RANGE_END],
            [self::STATE_INCLUDED_RANGE_LAST_TERM, QueryToken::TYPE_RIGHT_PARENTHESIS, self::STATE_EXCLUDED_RANGE_END],
        ]);

        $this->addTransitions([
            [self::STATE_EXCLUDED_RANGE_START, QueryToken::TYPE_WORD, self::STATE_EXCLUDED_RANGE_FIRST_TERM],
            [self::STATE_EXCLUDED_RANGE_START, QueryToken::TYPE_DATE, self::STATE_EXCLUDED_RANGE_FIRST_TERM],
            [self::STATE_EXCLUDED_RANGE_START, QueryToken::TYPE_NUMBER, self::STATE_EXCLUDED_RANGE_FIRST_TERM],
            [self::STATE_EXCLUDED_RANGE_FIRST_TERM, QueryToken::TYPE_TO_LEXEME, self::STATE_EXCLUDED_RANGE_TO_TERM],
            [self::STATE_EXCLUDED_RANGE_TO_TERM, QueryToken::TYPE_WORD, self::STATE_EXCLUDED_RANGE_LAST_TERM],
            [self::STATE_EXCLUDED_RANGE_TO_TERM, QueryToken::TYPE_DATE, self::STATE_EXCLUDED_RANGE_LAST_TERM],
            [self::STATE_EXCLUDED_RANGE_TO_TERM, QueryToken::TYPE_NUMBER, self::STATE_EXCLUDED_RANGE_LAST_TERM],
            [self::STATE_EXCLUDED_RANGE_LAST_TERM, QueryToken::TYPE_RIGHT_PARENTHESIS, self::STATE_EXCLUDED_RANGE_END],
            [self::STATE_EXCLUDED_RANGE_LAST_TERM, QueryToken::TYPE_RIGHT_SQUARE_BRACKET, self::STATE_INCLUDED_RANGE_END],
        ]);

        $addTermEntryAction = new FSMAction($this, 'addTermEntry');
        $addPhraseEntryAction = new FSMAction($this, 'addPhraseEntry');
        $operatorAction = new FSMAction($this, 'operator');
        $logicalOperatorAction = new FSMAction($this, 'logicalOperator');
        $subQueryStartAction = new FSMAction($this, 'subQueryStart');
        $subQueryEndAction = new FSMAction($this, 'subQueryEnd');
        $includedRangeFirstTermAction = new FSMAction($this, 'includedRangeFirstTerm');
        $includedRangeLastTermAction = new FSMAction($this, 'includedRangeLastTerm');
        $excludedRangeFirstTermAction = new FSMAction($this, 'excludedRangeFirstTerm');
        $excludedRangeLastTermAction = new FSMAction($this, 'excludedRangeLastTerm');

        $this->addInputAction(self::STATE_QUERY_ELEMENT, QueryToken::TYPE_WORD, $addTermEntryAction);
        $this->addInputAction(self::STATE_QUERY_ELEMENT, QueryToken::TYPE_PHRASE, $addPhraseEntryAction);
        $this->addInputAction(self::STATE_QUERY_ELEMENT, QueryToken::TYPE_OPERATOR, $operatorAction);
        $this->addInputAction(self::STATE_QUERY_ELEMENT, QueryToken::TYPE_AND_OPERATOR, $logicalOperatorAction);
        $this->addInputAction(self::STATE_QUERY_ELEMENT, QueryToken::TYPE_OR_OPERATOR, $logicalOperatorAction);
        $this->addInputAction(self::STATE_QUERY_ELEMENT, QueryToken::TYPE_NOT_OPERATOR, $logicalOperatorAction);
        //$this->addInputAction(self::STATE_QUERY_ELEMENT, QueryToken::TYPE_LEFT_PARENTHESIS, $subQueryStartAction);
        //$this->addInputAction(self::STATE_QUERY_ELEMENT, QueryToken::TYPE_RIGHT_PARENTHESIS, $subQueryEndAction);
        $this->addEntryAction(self::STATE_INCLUDED_RANGE_FIRST_TERM, $includedRangeFirstTermAction);
        $this->addEntryAction(self::STATE_INCLUDED_RANGE_END, $includedRangeLastTermAction);
        $this->addEntryAction(self::STATE_EXCLUDED_RANGE_FIRST_TERM, $excludedRangeFirstTermAction);
        $this->addEntryAction(self::STATE_EXCLUDED_RANGE_END, $excludedRangeLastTermAction);

        if (!$this->lexer instanceof QueryLexerInterface) {
            $this->lexer = Yii::createObject($this->lexer);
        }
    }

    /**
     * @inheritdoc
     */
    public function parse($queryString, QueryParserContextInterface $context = null)
    {
        $this->reset();
        $this->_parsingContext = $context;
        $this->_lastToken = null;
        $this->_context = new QueryParserContext($this->defaultOperator, $this->higherPriorityOperator);
        $this->_contextStack = [];
        $tokens = $this->lexer->tokenize($queryString);
        foreach ($tokens as $token) {
            $this->_currentToken = $token;
            $this->process($token->type);
            $this->_lastToken = $token;
        }
        if (count($this->_contextStack) !== 0) {
            throw new QueryParserException('Syntax Error: mismatched parentheses, every opening must have closing.' );
        }

        return $this->_context->getQuery();
    }

    /**
     * Adds terms entry to context.
     */
    public function addTermEntry()
    {
        $field = $this->_context->getField();
        if ($this->_parsingContext && $this->_lastToken && $this->_lastToken->type === QueryToken::TYPE_WORD
            && in_array($field, $this->_parsingContext->getTextFields())) {
            $entries = $this->_context->getEntries();
            $lastEntry = array_pop($entries);
            if ($lastEntry instanceof Condition) {
                $lastEntry->setValue($lastEntry->getValue() . ' ' . $this->_currentToken->text);
            }
            array_push($entries, $lastEntry);
            $this->_context->setEntries($entries);
        } else {
            $entry = new Condition($this->_context->getField(), $this->_currentToken->text, $this->_context->getOperator());
            $this->_context->addEntry($entry);
        }
    }

    /**
     * Add phrase to a query
     */
    public function addPhraseEntry()
    {
        $entry = new Phrase($this->_context->getField(), $this->_currentToken->text);
        $this->_context->addEntry($entry);
    }

    /**
     * Adds operator to context.
     */
    public function operator()
    {
        if (!$this->_lastToken || $this->_lastToken->type !== QueryToken::TYPE_WORD) {
            throw new QueryParserException('Syntax Error: operator must follow field.');
        }
        $this->_context->setField($this->_lastToken->text);
        $this->_context->setOperator($this->_currentToken->text);
    }

    /**
     * Adds logical operator to context.
     */
    public function logicalOperator()
    {
        $this->_context->setLogicalOperator($this->_currentToken->type);
    }

    /**
     * Starts sub query.
     */
    public function subQueryStart()
    {
        $this->_contextStack[] = $this->_context;
        $this->_context = new QueryParserContext($this->defaultOperator, $this->higherPriorityOperator, $this->_context->getField(), $this->_context->getOperator());
    }

    /**
     * Ends sub query
     */
    public function subQueryEnd()
    {
        if (count($this->_contextStack) == 0) {
            throw new QueryParserException("Syntax Error: mismatched parentheses, every opening must have closing. Char position {$this->_currentToken->position}");
        }
        $query = $this->_context->getQuery();
        $this->_context = array_pop($this->_contextStack);
        $this->_context->addEntry(new SubQuery($query));
    }

    public function includedRangeFirstTerm()
    {
        $this->_rangeFirstTerm = $this->_currentToken->text;
        $this->_includeRangeFirstTerm = true;
    }

    public function includedRangeLastTerm()
    {
        $from = $this->_rangeFirstTerm;
        $to = $this->_lastToken->text;
        if ($from === null  &&  $to === null) {
            throw new QueryParserException('Syntax Error: At least one range query boundary term must be non-empty term.');
        }
        $range = new Range($this->_context->getField(), $from, $to, $this->_includeRangeFirstTerm, true);
        $this->_context->addEntry(new SubQuery($range));
    }

    public function excludedRangeFirstTerm()
    {
        $this->_rangeFirstTerm = $this->_currentToken->text;
        $this->_includeRangeFirstTerm = false;
    }

    public function excludedRangeLastTerm()
    {
        $from = $this->_rangeFirstTerm;
        $to = $this->_lastToken->text;
        if ($from === null  &&  $to === null) {
            throw new QueryParserException('Syntax Error: At least one range query boundary term must be non-empty term.');
        }
        $range = new Range($this->_context->getField(), $from, $to, $this->_includeRangeFirstTerm, false);
        $this->_context->addEntry(new SubQuery($range));
    }
}