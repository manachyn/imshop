<?php

namespace im\search\components\query\parser;

/**
 * Parsed query.
 *
 * @package im\search\components\query\parser
 */
class Query implements QueryInterface
{
    private $_entries;

    function __construct($entries)
    {
        $this->_entries = $entries;
    }
}