<?php

namespace im\search\components\query;

/**
 * Interface SuggestionsQueryResultInterface
 * @package im\search\components\query
 */
interface SuggestionsQueryResultInterface
{
    /**
     * @return SuggestionOption
     */
    public function getSuggestions();

    /**
     * @return SuggestionsQueryInterface
     */
    public function getQuery();
}