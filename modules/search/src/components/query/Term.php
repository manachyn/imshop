<?php

namespace im\search\components\query;

/**
 * Term query.
 *
 * @package im\search\components\query\parser\entry
 */
class Term extends Query
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
     * Creates term.
     *
     * @param string $field
     * @param string $term
     */
    function __construct($field, $term)
    {
        $this->_field = $field;
        $this->_term = $term;
    }
}