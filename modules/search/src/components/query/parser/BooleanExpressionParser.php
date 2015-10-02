<?php

namespace im\search\components\query\parser;

use im\fsm\FSM;
use im\fsm\FSMAction;

/**
 * Boolean expression parser.
 *
 * @package im\search\components\query\parser
 */
class BooleanExpressionParser extends FSM
{
    /**
     * State Machine states
     */
    const ST_START           = 0;
    const ST_LITERAL         = 1;
    const ST_NOT_OPERATOR    = 2;
    const ST_AND_OPERATOR    = 3;
    const ST_OR_OPERATOR     = 4;

    /**
     * Input symbols
     */
    const IN_LITERAL         = 0;
    const IN_NOT_OPERATOR    = 1;
    const IN_AND_OPERATOR    = 2;
    const IN_OR_OPERATOR     = 3;

    /**
     * Default boolean operator
     *
     * @var int
     */
    private $_defaultOperator;

    /**
     * Boolean operator which has higher priority
     *
     * @var int
     */
    private $_higherPriorityOperator = QueryParser::OPERATOR_AND;

    /**
     * NOT operator signal
     *
     * @var boolean
     */
    private $_negativeLiteral = false;

    /**
     * Current literal
     *
     * @var mixed
     */
    private $_literal;


    /**
     * Set of boolean query conjunctions
     *
     * Each conjunction is an array of conjunction elements
     * Each conjunction element is presented with two-elements array:
     * array(<literal>, <is_negative>)
     *
     * So, it has a structure:
     * array( array( array(<literal>, <is_negative>), // first literal of first conjunction
     *               array(<literal>, <is_negative>), // second literal of first conjunction
     *               ...
     *               array(<literal>, <is_negative>)
     *             ), // end of first conjunction
     *        array( array(<literal>, <is_negative>), // first literal of second conjunction
     *               array(<literal>, <is_negative>), // second literal of second conjunction
     *               ...
     *               array(<literal>, <is_negative>)
     *             ), // end of second conjunction
     *        ...
     *      ) // end of structure
     *
     * @var array
     */
    private $_conjunctions = [];

    /**
     * Current conjunction
     *
     * @var array
     */
    private $_currentConjunction = array();

    /**
     * @param int $defaultOperator
     * @param array|int $higherPriorityOperator
     */
    public function __construct($defaultOperator, $higherPriorityOperator = QueryParser::OPERATOR_AND)
    {
        $this->_defaultOperator = $defaultOperator;
        $this->_higherPriorityOperator = $higherPriorityOperator;
        parent::__construct(
            [
                self::ST_START,
                self::ST_LITERAL,
                self::ST_NOT_OPERATOR,
                self::ST_AND_OPERATOR,
                self::ST_OR_OPERATOR
            ],
            [
                self::IN_LITERAL,
                self::IN_NOT_OPERATOR,
                self::IN_AND_OPERATOR,
                self::IN_OR_OPERATOR
            ]
        );

        $emptyOperatorAction = new FSMAction($this, 'emptyOperatorAction');
        $emptyNotOperatorAction = new FSMAction($this, 'emptyNotOperatorAction');

        $this->addTransitions([
            [self::ST_START, self::IN_LITERAL, self::ST_LITERAL],
            [self::ST_START, self::IN_NOT_OPERATOR, self::ST_NOT_OPERATOR],
            [self::ST_LITERAL, self::IN_AND_OPERATOR, self::ST_AND_OPERATOR],
            [self::ST_LITERAL, self::IN_OR_OPERATOR, self::ST_OR_OPERATOR],
            [self::ST_LITERAL, self::IN_LITERAL, self::ST_LITERAL, $emptyOperatorAction],
            [self::ST_LITERAL, self::IN_NOT_OPERATOR, self::ST_NOT_OPERATOR, $emptyNotOperatorAction],
            [self::ST_NOT_OPERATOR, self::IN_LITERAL, self::ST_LITERAL],
            [self::ST_AND_OPERATOR, self::IN_LITERAL, self::ST_LITERAL],
            [self::ST_AND_OPERATOR, self::IN_NOT_OPERATOR, self::ST_NOT_OPERATOR],
            [self::ST_OR_OPERATOR, self::IN_LITERAL, self::ST_LITERAL],
            [self::ST_OR_OPERATOR, self::IN_NOT_OPERATOR, self::ST_NOT_OPERATOR]
        ]);

        $notOperatorAction = new FSMAction($this, 'notOperatorAction');
        $orOperatorAction = new FSMAction($this, 'orOperatorAction');
        $andOperatorAction = new FSMAction($this, 'andOperatorAction');
        $literalAction = new FSMAction($this, 'literalAction');

        $this->addEntryAction(self::ST_NOT_OPERATOR, $notOperatorAction);
        $this->addEntryAction(self::ST_LITERAL, $literalAction);
        if ($this->_higherPriorityOperator === QueryParser::OPERATOR_AND) {
            $this->addEntryAction(self::ST_OR_OPERATOR, $orOperatorAction);
        } else {
            $this->addEntryAction(self::ST_AND_OPERATOR, $andOperatorAction);
        }
    }


    /**
     * Process next operator.
     *
     * Operators are defined by class constants: IN_AND_OPERATOR, IN_OR_OPERATOR and IN_NOT_OPERATOR
     *
     * @param integer $operator
     */
    public function processOperator($operator)
    {
        $this->process($operator);
    }

    /**
     * Process expression literal.
     *
     * @param mixed $literal
     */
    public function processLiteral($literal)
    {
        $this->_literal = $literal;
        $this->process(self::IN_LITERAL);
    }

    /**
     * Finish an expression and return result
     *
     * Result is a set of boolean query conjunctions
     *
     * Each conjunction is an array of conjunction elements
     * Each conjunction element is presented with two-elements array:
     * array(<literal>, <is_negative>)
     *
     * So, it has a structure:
     * array( array( array(<literal>, <is_negative>), // first literal of first conjunction
     *               array(<literal>, <is_negative>), // second literal of first conjunction
     *               ...
     *               array(<literal>, <is_negative>)
     *             ), // end of first conjunction
     *        array( array(<literal>, <is_negative>), // first literal of second conjunction
     *               array(<literal>, <is_negative>), // second literal of second conjunction
     *               ...
     *               array(<literal>, <is_negative>)
     *             ), // end of second conjunction
     *        ...
     *      ) // end of structure
     *
     * @throws \ZendSearch\Lucene\Exception\UnexpectedValueException
     * @return array
     */
    public function finishExpression()
    {
        if ($this->getState() != self::ST_LITERAL) {
            throw new \UnexpectedValueException('Literal expected.');
        }
        $this->_conjunctions[] = $this->_currentConjunction;

        return $this->_conjunctions;
    }

    /**
     * Default (omitted) operator processing
     */
    public function emptyOperatorAction()
    {
        if ($this->_defaultOperator == QueryParser::OPERATOR_AND) {
            // Do nothing
        } else {
            $this->orOperatorAction();
        }
        // Process literal
        $this->literalAction();
    }

    /**
     * default (omitted) + NOT operator processing
     */
    public function emptyNotOperatorAction()
    {
        if ($this->_defaultOperator == QueryParser::OPERATOR_AND) {
            // Do nothing
        } else {
            $this->orOperatorAction();
        }
        // Process NOT operator
        $this->notOperatorAction();
    }


    /**
     * NOT operator processing
     */
    public function notOperatorAction()
    {
        $this->_negativeLiteral = true;
    }

    /**
     * OR operator processing
     * Close current conjunction
     */
    public function orOperatorAction()
    {
        $this->_conjunctions[] = $this->_currentConjunction;
        $this->_currentConjunction = [];
    }

    /**
     * AND operator processing
     * Close current conjunction
     */
    public function andOperatorAction()
    {
        $this->_conjunctions[] = $this->_currentConjunction;
        $this->_currentConjunction = [];
    }

    /**
     * Literal processing
     */
    public function literalAction()
    {
        // Add literal to the current conjunction
        $this->_currentConjunction[] = array($this->_literal, !$this->_negativeLiteral);

        // Switch off negative signal
        $this->_negativeLiteral = false;
    }
}
