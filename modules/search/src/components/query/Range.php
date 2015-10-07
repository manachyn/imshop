<?php

namespace im\search\components\query;

use yii\base\InvalidParamException;

/**
 * Range query.
 *
 * @package im\search\components\query
 */
class Range extends Query implements FieldQueryInterface, RangeInterface
{
    /**
     * @var string
     */
    private $_field;

    /**
     * @var mixed
     */
    private $_lowerBound;

    /**
     * @var mixed
     */
    private $_upperBound;

    /**
     * @var bool
     */
    private $_includeLowerBound = true;

    /**
     * @var bool
     */
    private $_includeUpperBound = false;

    /**
     * Creates range query.
     *
     * @param string $field
     * @param mixed $lowerBound
     * @param mixed $upperBound
     * @param bool $includeLowerBound
     * @param bool $includeUpperBound
     * @throws \yii\base\InvalidParamException
     */
    function __construct($field, $lowerBound = null, $upperBound = null, $includeLowerBound = true, $includeUpperBound = false)
    {
        if ($lowerBound === null && $upperBound === null) {
            throw new InvalidParamException('At least one range bound must be set.');
        }
        $this->_field =$field;
        $this->_lowerBound = $lowerBound;
        $this->_upperBound = $upperBound;
        $this->_includeLowerBound = $includeLowerBound;
        $this->_includeUpperBound = $includeUpperBound;
    }

    /**
     * @inheritdoc
     */
    public function getField()
    {
        return $this->_field;
    }

    /**
     * Sets query field.
     *
     * @param string $field
     */
    public function setField($field)
    {
        $this->_field = $field;
    }

    /**
     * @inheritdoc
     */
    public function getLowerBound()
    {
        return $this->_lowerBound;
    }

    /**
     * @inheritdoc
     */
    public function getUpperBound()
    {
        return $this->_upperBound;
    }

    /**
     * @inheritdoc
     */
    public function isIncludeLowerBound()
    {
        return $this->_includeLowerBound;
    }

    /**
     * @inheritdoc
     */
    public function isIncludeUpperBound()
    {
        return $this->_includeUpperBound;
    }
} 