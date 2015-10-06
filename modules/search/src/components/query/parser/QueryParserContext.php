<?php

namespace im\search\components\query\parser;

use im\search\components\query\Boolean;
use im\search\components\query\parser\entry\QueryEntryInterface;
use im\search\components\query\SearchQueryInterface;

/**
 * Query parser context.
 *
 * @package im\search\components\query\parser
 */
class QueryParserContext
{
    /**
     * @var string
     */
    private $_field;

    /**
     * @var mixed
     */
    private $_operator;

    /**
     * @var int
     */
    private $_defaultLogicalOperator;

    /**
     * @var int
     */
    private $_higherPriorityLogicalOperator;

    /**
     * @var QueryEntryInterface[]
     */
    private $_entries = [];

    /**
     * @param int $defaultLogicalOperator
     * @param int $higherPriorityLogicalOperator
     * @param string|null $field
     * @param string|null $operator
     */
    function __construct($defaultLogicalOperator, $higherPriorityLogicalOperator = QueryParser::OPERATOR_AND, $field = null, $operator = null)
    {
        $this->_defaultLogicalOperator = $defaultLogicalOperator;
        $this->_higherPriorityLogicalOperator = $higherPriorityLogicalOperator;
        $this->_field = $field;
        $this->_operator = $operator;
    }

    /**
     * Returns context field.
     *
     * @return null|string
     */
    public function getField()
    {
        return $this->_field;
    }

    /**
     * Sets context field.
     *
     * @param string $field
     */
    public function setField($field)
    {
        $this->_field = $field;
    }

    /**
     * Returns context operator.
     *
     * @return null|string
     */
    public function getOperator()
    {
        return $this->_operator;
    }

    /**
     * Sets context operator.
     *
     * @param mixed $operator
     */
    public function setOperator($operator)
    {
        $this->_operator = $operator;
        array_pop($this->_entries);
    }

    /**
     * Sets context logical operator.
     *
     * @param mixed $operator
     */
    public function setLogicalOperator($operator)
    {
        $this->_entries[] = $operator;
    }

    /**
     * Adds query entry to context.
     *
     * @param QueryEntryInterface $entry
     */
    public function addEntry($entry)
    {
        $this->_entries[] = $entry;
    }

    /**
     * Return query entries from context.
     *
     * @return QueryEntryInterface[]
     */
    public function getEntries()
    {
        return $this->_entries;
    }

    /**
     * Returns query from context.
     *
     * @throws QueryParserException
     * @return SearchQueryInterface
     */
    public function getQuery()
    {
        /**
         * We treat each level of an expression as a boolean expression in
         * a Disjunctive Normal Form
         *
         * AND operator has higher precedence than OR
         *
         * Thus logical query is a disjunction of one or more conjunctions of
         * one or more query entries
         */

        $expressionParser = new BooleanExpressionParser($this->_defaultLogicalOperator, $this->_higherPriorityLogicalOperator);

        try {
            foreach ($this->_entries as $entry) {
                if ($entry instanceof QueryEntryInterface) {
                    $expressionParser->processLiteral($entry);
                } else {
                    switch ($entry) {
                        case QueryToken::TYPE_AND_OPERATOR:
                            $expressionParser->processOperator(BooleanExpressionParser::IN_AND_OPERATOR);
                            break;
                        case QueryToken::TYPE_OR_OPERATOR:
                            $expressionParser->processOperator(BooleanExpressionParser::IN_OR_OPERATOR);
                            break;
                        case QueryToken::TYPE_NOT_OPERATOR:
                            $expressionParser->processOperator(BooleanExpressionParser::IN_NOT_OPERATOR);
                            break;
                        default:
                            throw new \UnexpectedValueException('Boolean expression error. Unknown operator type.');
                    }
                }
            }

            $conjunctions = $expressionParser->finishExpression();
        } catch (\Exception $e) {
            // It's query syntax error message and it should be user friendly. So FSM message is omitted
            throw new QueryParserException('Boolean expression error.', 0, $e);
        }

        // Remove 'only negative' conjunctions
        foreach ($conjunctions as $conjunctionId => $conjunction) {
            $nonNegativeEntryFound = false;
            foreach ($conjunction as $conjunctionEntry) {
                if ($conjunctionEntry[1]) {
                    $nonNegativeEntryFound = true;
                    break;
                }
            }
            if (!$nonNegativeEntryFound) {
                unset($conjunctions[$conjunctionId]);
            }
        }

        $subQueries = array();
        foreach ($conjunctions as  $conjunction) {
            // Check, if it's a one term conjunction
            if (count($conjunction) === 1) {
                /** @var QueryEntryInterface $entry */
                $entry = $conjunction[0][0];
                $subQueries[] = $entry->getQuery();
            } else {
                $subQuery = new Boolean();
                foreach ($conjunction as $conjunctionEntry) {
                    $entry = $conjunctionEntry[0];
                    $subQuery->addSubquery($entry->getQuery(), $conjunctionEntry[1]);
                }
                $subQueries[] = $subQuery;
            }
        }

        if (count($subQueries) == 1) {
            return $subQueries[0];
        }

        $query = new Boolean();

        foreach ($subQueries as $subQuery) {
            $query->addSubquery($subQuery, true);
        }

        return $query;
    }
}