<?php

namespace im\search\components\query;

/**
 * Class SuggestionsQueryResult
 * @package im\search\components\query
 */
abstract class SuggestionsQueryResult implements SuggestionsQueryResultInterface
{
    /**
     * @var SuggestionsQueryInterface
     */
    protected $query;

    /**
     * @param SuggestionsQueryInterface $query
     */
    function __construct(SuggestionsQueryInterface $query)
    {
        $this->query = $query;
    }

    /**
     * @inheritdoc
     */
    public function getQuery()
    {
        return $this->query;
    }

    /**
     * @param SuggestionsQueryInterface $query
     */
    public function setQuery($query)
    {
        $this->query = $query;
    }
}