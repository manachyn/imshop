<?php

namespace im\search\components\query\parser\entry;

use im\search\components\query\QueryInterface;

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
     * @return QueryInterface
     */
    public function getQuery();
}