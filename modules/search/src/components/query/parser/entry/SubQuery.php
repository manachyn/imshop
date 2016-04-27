<?php

namespace im\search\components\query\parser\entry;

use im\search\components\query\SearchQueryInterface;


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
     * @var SearchQueryInterface
     */
    private $_query;

    /**
     * @param SearchQueryInterface $query
     */
    public function __construct(SearchQueryInterface $query)
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
