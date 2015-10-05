<?php

namespace im\search\components\query;

/**
 * Boolean query.
 *
 * @package im\search\components\query
 */
class Boolean extends Query
{
    /**
     * @var QueryInterface[]
     */
    private $_subQueries = [];

    /**
     * @param QueryInterface[] $subQueries
     */
    public function setSubQueries($subQueries = [])
    {
        $this->_subQueries = $subQueries;
    }

    /**
     * @return QueryInterface[]
     */
    public function getSubQueries()
    {
        return $this->_subQueries;
    }

    /**
     * @param QueryInterface $subQuery
     */
    public function addSubQuery(QueryInterface $subQuery)
    {
        $this->_subQueries[] = $subQuery;
    }
} 