<?php

namespace im\search\components\query\parser\entry;

use im\search\components\query\SearchQueryInterface;

/**
 * Interface QueryEntryInterface.
 *
 * @package im\search\components\query\parser\entry
 */
interface QueryEntryInterface
{
    /**
     * Converts query entry to query.
     *
     * @return SearchQueryInterface
     */
    public function getQuery();
}