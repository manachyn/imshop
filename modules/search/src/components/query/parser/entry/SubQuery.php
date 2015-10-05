<?php

namespace im\search\components\query\parser\entry;

use im\search\components\query\QueryInterface;

/**
 * Sub query entry.
 *
 * @package im\search\components\query\parser\entry
 */
class SubQuery implements QueryEntryInterface
{
    /**
     * Query instance
     *
     * @var QueryInterface
     */
    private $_query;

    /**
     * @param QueryInterface $query
     */
    public function __construct(QueryInterface $query)
    {
        $this->_query = $query;
    }

    /**
     * @inheritdoc
     */
    public function getQuery()
    {
        return $this->_query;
    }
}
