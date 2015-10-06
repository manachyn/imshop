<?php

namespace im\search\components\query\parser;

use im\search\components\query\SearchQueryInterface;

/**
 * Query parser interface.
 *
 * @package im\search\components\query\parser
 */
interface QueryParserInterface
{
    /**
     * Parses query string.
     *
     * @param string $queryString
     * @return SearchQueryInterface
     */
    public function parse($queryString);

    /**
     * Converts query to string.
     *
     * @param SearchQueryInterface $query
     * @return string
     */
    public function toString(SearchQueryInterface $query);
}