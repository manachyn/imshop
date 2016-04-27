<?php

namespace im\search\components\query\parser\entry;
use im\search\components\query\parser\Operator;
use im\search\components\query\Range;
use im\search\components\query\Term;

/**
 * Condition query entry,
 *
 * @package im\search\components\query\parser\entry
 */
class Condition implements QueryEntryInterface
{
    /**
     * @var string
     */
    private $_field;

    /**
     * @var string
     */
    private $_value;

    /**
     * @var string
     */
    private $_operator;

    /**
     * @param string $field
     * @param string $value
     * @param string $operator
     */
    function __construct($field, $value, $operator)
    {
        $this->_field = $field;
        $this->_value = $value;
        $this->_operator = $operator;
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->_field;
    }

    /**
     * @param string $field
     */
    public function setField($field)
    {
        $this->_field = $field;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->_value;
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->_value = $value;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->_operator;
    }

    /**
     * @param string $operator
     */
    public function setOperator($operator)
    {
        $this->_operator = $operator;
    }

    /**
     * @inheritdoc
     */
    public function getQuery()
    {
        switch ($this->_operator) {
            case Operator::GT:
                $query = new Range($this->_field, $this->_value, null, false);
                break;
            case Operator::GTE:
                $query = new Range($this->_field, $this->_value);
                break;
            case Operator::LT:
                $query = new Range($this->_field, null, $this->_value, true, false);
                break;
            case Operator::LTE:
                $query = new Range($this->_field, null, $this->_value, true, true);
                break;
            default:
                $query = new Term($this->_field, $this->_value);
        }

        return $query;
    }
}