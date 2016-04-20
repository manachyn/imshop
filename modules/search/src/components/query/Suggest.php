<?php

namespace im\search\components\query;

/**
 * Suggest search query.
 *
 * @package im\search\components\query
 */
class Suggest extends SearchQuery
{
    /**
     * @var array
     */
    private $_fields;

    /**
     * @var string
     */
    private $_term;

    /**
     * Term constructor.
     * @param array $fields
     * @param string $term
     */
    function __construct($fields, $term)
    {
        $this->_fields = $fields;
        $this->_term = $term;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * @param array $fields
     */
    public function setFields($fields)
    {
        $this->_fields = $fields;
    }

    /**
     * Returns query term.
     *
     * @return string
     */
    public function getTerm()
    {
        return $this->_term;
    }

    /**
     * Sets query term.
     *
     * @param string $term
     */
    public function setTerm($term)
    {
        $this->_term = $term;
    }
}