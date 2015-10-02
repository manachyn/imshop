<?php

namespace im\search\components\query\parser;

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
     * @return QueryInterface
     */
    public function parse($queryString);
}