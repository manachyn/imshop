<?php

namespace im\search\components\query;

/**
 * Search query interface.
 *
 * @package im\search\components\query
 */
interface SearchQueryInterface
{
    /**
     * Whether search query is empty.
     *
     * @return bool
     */
    public function isEmpty();
}