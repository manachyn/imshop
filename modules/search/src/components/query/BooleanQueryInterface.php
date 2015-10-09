<?php

namespace im\search\components\query;

interface BooleanQueryInterface extends SearchQueryInterface
{
    /**
     * Returns sub queries.
     *
     * @return SearchQueryInterface[]
     */
    public function getSubQueries();

    /**
     * Sets sub queries.
     *
     * @param SearchQueryInterface[] $subQueries
     * @param array $signs
     */
    public function setSubQueries($subQueries, $signs = null);

    /**
     * Adds sub query.
     *
     * @param SearchQueryInterface $subQuery
     * @param bool|null $sign
     */
    public function addSubQuery(SearchQueryInterface $subQuery, $sign = null);

    /**
     * Returns signs of sub queries.
     *
     * @return array
     */
    public function getSigns();
}