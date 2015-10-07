<?php

namespace im\search\components\query\parser;

use im\fsm\FSM;
use im\fsm\FSMAction;
use im\search\components\query\Boolean;
use im\search\components\query\BooleanQueryInterface;
use im\search\components\query\FieldQueryInterface;
use im\search\components\query\parser\entry\Condition;
use im\search\components\query\parser\entry\SubQuery;
use im\search\components\query\Range;
use im\search\components\query\RangeInterface;
use im\search\components\query\SearchQueryInterface;
use im\search\components\query\Term;
use Yii;

/**
 * Finite State Machine based query parser.
 *
 * @package im\search\components\query\parser
 */
class QueryParser extends FSM implements QueryParserInterface
{
    /**
     * Boolean operators constants
     */
    const OPERATOR_OR = 0;
    const OPERATOR_AND = 1;

    /**
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
            [self::STATE_QUERY_ELEMENT, QueryToken::TYPE_LEFT_PARENTHESIS, self::STATE_QUERY_ELEMENT],
            [self::STATE_QUERY_ELEMENT, QueryToken::TYPE_RIGHT_PARENTHESIS, self::STATE_QUERY_ELEMENT]
        ]);

        $this->addTransitions([
            [self::STATE_INCLUDED_RANGE_START, QueryToken::TYPE_WORD, self::STATE_INCLUDED_RANGE_FIRST_TERM],
            [self::STATE_INCLUDED_RANGE_START, QueryToken::TYPE_DATE, self::STATE_INCLUDED_RANGE_FIRST_TERM],
            [self::STATE_INCLUDED_RANGE_START, QueryToken::TYPE_NUMBER, self::STATE_INCLUDED_RANGE_FIRST_TERM],
            [self::STATE_INCLUDED_RANGE_FIRST_TERM, QueryToken::TYPE_TO_LEXEME, self::STATE_INCLUDED_RANGE_TO_TERM],
            [self::STATE_INCLUDED_RANGE_TO_TERM, QueryToken::TYPE_WORD, self::STATE_INCLUDED_RANGE_LAST_TERM],
            [self::STATE_INCLUDED_RANGE_TO_TERM, QueryToken::TYPE_DATE, self::STATE_INCLUDED_RANGE_LAST_TERM],
            [self::STATE_INCLUDED_RANGE_TO_TERM, QueryToken::TYPE_NUMBER, self::STATE_INCLUDED_RANGE_LAST_TERM],
            [self::STATE_INCLUDED_RANGE_LAST_TERM, QueryToken::TYPE_RIGHT_SQUARE_BRACKET, self::STATE_QUERY_ELEMENT]
        ]);

        $this->addTransitions([
            [self::STATE_EXCLUDED_RANGE_START, QueryToken::TYPE_WORD, self::STATE_EXCLUDED_RANGE_FIRST_TERM],
            [self::STATE_EXCLUDED_RANGE_START, QueryToken::TYPE_DATE, self::STATE_EXCLUDED_RANGE_FIRST_TERM],
            [self::STATE_EXCLUDED_RANGE_START, QueryToken::TYPE_NUMBER, self::STATE_EXCLUDED_RANGE_FIRST_TERM],
            [self::STATE_EXCLUDED_RANGE_FIRST_TERM, QueryToken::TYPE_TO_LEXEME, self::STATE_EXCLUDED_RANGE_TO_TERM],
            [self::STATE_EXCLUDED_RANGE_TO_TERM, QueryToken::TYPE_WORD, self::STATE_EXCLUDED_RANGE_LAST_TERM],
            [self::STATE_EXCLUDED_RANGE_TO_TERM, QueryToken::TYPE_DATE, self::STATE_EXCLUDED_RANGE_LAST_TERM],
            [self::STATE_EXCLUDED_RANGE_TO_TERM, QueryToken::TYPE_NUMBER, self::STATE_EXCLUDED_RANGE_LAST_TERM],
            [self::STATE_EXCLUDED_RANGE_LAST_TERM, QueryToken::TYPE_RIGHT_PARENTHESIS, self::STATE_QUERY_ELEMENT]
        ]);

        $addTermEntryAction = new FSMAction($this, 'addTermEntry');
        $operatorAction = new FSMAction($this, 'operator');
        $logicalOperatorAction = new FSMAction($this, 'logicalOperator');
        $subQueryStartAction = new FSMAction($this, 'subQueryStart');
        $subQueryEndAction = new FSMAction($this, 'subQueryEnd');
        $includedRangeFirstTermAction = new FSMAction($this, 'includedRangeFirstTerm');
        $includedRangeLastTermAction = new FSMAction($this, 'includedRangeLastTerm');

        $this->addInputAction(self::STATE_QUERY_ELEMENT, QueryToken::TYPE_WORD, $addTermEntryAction);
        $this->addInputAction(self::STATE_QUERY_ELEMENT, QueryToken::TYPE_OPERATOR, $operatorAction);
        $this->addInputAction(self::STATE_QUERY_ELEMENT, QueryToken::TYPE_AND_OPERATOR, $logicalOperatorAction);
        $this->addInputAction(self::STATE_QUERY_ELEMENT, QueryToken::TYPE_OR_OPERATOR, $logicalOperatorAction);
        $this->addInputAction(self::STATE_QUERY_ELEMENT, QueryToken::TYPE_NOT_OPERATOR, $logicalOperatorAction);
        $this->addInputAction(self::STATE_QUERY_ELEMENT, QueryToken::TYPE_LEFT_PARENTHESIS, $subQueryStartAction);
        $this->addInputAction(self::STATE_QUERY_ELEMENT, QueryToken::TYPE_RIGHT_PARENTHESIS, $subQueryEndAction);
        $this->addEntryAction(self::STATE_INCLUDED_RANGE_FIRST_TERM, $includedRangeFirstTermAction);
        $this->addEntryAction(self::STATE_INCLUDED_RANGE_LAST_TERM, $includedRangeLastTermAction);

        if (!$this->lexer instanceof QueryLexerInterface) {
            $this->lexer = Yii::createObject($this->lexer);
        }
    }

    /**
     * @inheritdoc
     */
    public function parse($queryString)
    {
        $this->reset();
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
     * @inheritdoc
     */
    public function toString(SearchQueryInterface $query)
    {
        $queryString = '';
        if ($query instanceof BooleanQueryInterface) {
            $subQueries = $query->getSubQueries();
            $signs = $query->getSigns();
            $lastSign = reset($signs);
            $lastQuery = reset($subQueries);
            $same = true;
            foreach ($subQueries as $key => $subQuery) {
                if ($signs[$key] !== $sign) {
                    $same = false;
                    $subQuery1 = new Boolean(array_slice($subQueries, $key), array_slice($signs, $key));
                    $subQuery2 = new Boolean(array_slice($subQueries, $key), array_slice($signs, $key));
                    $queryString = implode($sign, [$this->toString($subQuery1), $this->toString($subQuery2)]);
                    break;
                }
                $sign = $signs[$key];
            }
            if ($same) {
//                $queryString = implode($sign, array_map(function(SearchQueryInterface $query) {
//                    return $this->toString($query);
//                }, $subQueries));
//                $a = 1;
            }
        } elseif ($query instanceof FieldQueryInterface) {
            if ($query instanceof RangeInterface) {
                $queryString = $query->isIncludeLowerBound() ? '[' : '(';
                $queryString .= $query->getLowerBound() !== null ? $query->getLowerBound() : '';
                $queryString .= ' to ';
                $queryString .= $query->getUpperBound() !== null ? $query->getUpperBound() : '';
                $queryString .= $query->isIncludeUpperBound() ? ']' : ')';
            } elseif ($query instanceof Term) {
                $queryString = $query->getTerm();
            }
            if ($queryString) {
                $queryString = $query->getField() . '=' . $queryString;
            }
        }

        return $queryString;
    }

    /**
     * Adds terms entry to context.
     */
    public function addTermEntry()
    {
        $entry = new Condition($this->_context->getField(), $this->_currentToken->text, $this->_context->getOperator());
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
    }

    public function includedRangeLastTerm()
    {
        $from = $this->_rangeFirstTerm;
        $to = $this->_currentToken->text;
        if ($from === null  &&  $to === null) {
            throw new QueryParserException('Syntax Error: At least one range query boundary term must be non-empty term.');
        }
        $range = new Range($this->_context->getField(), $from, $to, true, true);
        //$range = new Range($this->_context->getField(), $from, $to, true, true);
        $this->_context->addEntry(new SubQuery($range));
    }
}