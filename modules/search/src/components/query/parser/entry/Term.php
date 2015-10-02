<?php

namespace im\search\components\query\parser\entry;

/**
 * Term query entry.
 *
 * @package im\search\components\query\parser\entry
 */
class Term implements QueryEntryInterface
{
    /**
     * @var string
     */
    private $_field;

    /**
     * @var string
     */
    private $_term;

    /**
     * @var string
     */
    private $_operator;

    /**
     * @param string $term
     * @param string $field
     * @param string $operator
     */
    function __construct($term, $field, $operator)
    {
        $this->_term = $term;
        $this->_field = $field;
        $this->_operator = $operator;
    }
}