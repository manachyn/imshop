<?php

namespace im\search\components\query;

/**
 * Multi match query.
 *
 * @package im\search\components\query
 */
class MultiMatch extends SearchQuery implements FieldQueryInterface
{
    /**
     * @var array
     */
    private $_fields;

    /**
     * @var Term
     */
    private $_term;

    /**
     * Creates Math query.
     *
     * @param array $fields
     * @param Term $term
     */
    function __construct($fields, Term $term)
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
     * @return Term
     */
    public function getTerm()
    {
        return $this->_term;
    }

    /**
     * @param Term $term
     */
    public function setTerm($term)
    {
        $this->_term = $term;
    }

    /**
     * @inheritdoc
     */
    public function getField()
    {
        return $this->getTerm()->getField();
    }

    /**
     * @inheritdoc
     */
    public function equals(SearchQueryInterface $query)
    {
        $sameField = $query instanceof MultiMatch && $this->getFields() === $query->getFields() && $this->getField() === $query->getField();

        return $sameField && $this->getTerm()->equals($query->getTerm()) ? 1 : ($sameField ? 0 : -1);
    }
}