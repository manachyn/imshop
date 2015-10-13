<?php

namespace im\search\components\finder;

use im\search\components\query\QueryInterface;
use im\search\components\query\SearchQueryInterface;

/**
 * Interface FinderInterface.
 *
 * @package im\search\components\finder
 */
interface FinderInterface
{
    /**
     * Finds by type.
     *
     * @param string $type
     * @return QueryInterface
     */
    public function find($type);

    /**
     * Finds by type and search query.
     *
     * @param string $type
     * @param SearchQueryInterface $query
     * @return QueryInterface
     */
    public function findByQuery($type, SearchQueryInterface $query);
}