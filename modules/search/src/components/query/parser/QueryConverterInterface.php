<?php

namespace im\search\components\query\parser;

use im\search\components\query\SearchQueryInterface;

/**
 * Query converter interface.
 *
 * @package im\search\components\query\parser
 */
interface QueryConverterInterface
{
    /**
     * Converts query to string.
     *
     * @param SearchQueryInterface $query
     * @return string
     */
    public function toString(SearchQueryInterface $query);
}