<?php

namespace im\search\components\service\db;

use im\search\components\query\QueryInterface;
use im\search\components\query\QueryResultInterface;

/**
 * Class QueryResult
 * @package im\search\components\service\db
 */
class QueryResult extends \im\search\components\query\QueryResult implements QueryResultInterface
{
    /**
     * @param QueryInterface $query
     * @param array $objects
     */
    function __construct(QueryInterface $query, array $objects)
    {
        parent::__construct($query);
        $this->objects = $objects;
        $this->init();
    }
    /**
     * @inheritdoc
     */
    public function getSelectedFacets()
    {
        return [];
    }

    /**
     * Init result
     */
    protected function init()
    {

    }
}