<?php

namespace im\search\components\query;

/**
 * Class QueryResult
 * @package im\search\components\query
 */
abstract class QueryResult implements QueryResultInterface
{
    /**
     * @var int total count of query results
     */
    protected $total;

    /**
     * @var array query result objects
     */
    protected $objects = [];

    /**
     * @var QueryInterface
     */
    protected $query;

    /**
     * @param QueryInterface $query
     */
    function __construct(QueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @inheritdoc
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal($total)
    {
        $this->total = $total;
    }

    /**
     * @inheritdoc
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * @param array $objects
     */
    public function setObjects($objects)
    {
        $this->objects = $objects;
    }

    /**
     * @inheritdoc
     */
    public function getFacets()
    {
        return $this->getQuery()->getFacets();
    }

    /**
     * @inheritdoc
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param QueryInterface $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }
}