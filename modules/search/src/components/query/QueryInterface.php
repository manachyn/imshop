<?php

namespace im\search\components\query;

use im\search\components\query\facet\FacetInterface;
use yii\db\Connection;

interface QueryInterface extends \yii\db\QueryInterface
{
    /**
     * @event Event an event that is triggered after getting query result.
     */
    const EVENT_AFTER_RESULT = 'afterResult';

    /**
     * Sets search query.
     *
     * @param SearchQueryInterface $searchQuery
     */
    public function setSearchQuery(SearchQueryInterface $searchQuery);

    /**
     * Returns search query.
     *
     * @return SearchQueryInterface
     */
    public function getSearchQuery();

    /**
     * Returns order.
     *
     * @return array
     */
    public function getOrderBy();

    /**
     * Executes the query and returns result object.
     *
     * @param Connection $db the service connection used to execute the query.
     * @return QueryResultInterface
     */
    public function result($db = null);

    /**
     * Sets query facets.
     *
     * @param FacetInterface[] $facets
     */
    public function setFacets($facets);

    /**
     * Adds an facet to this query.
     *
     * @param FacetInterface $facet
     * @return static the query object itself
     */
    public function addFacet(FacetInterface $facet);

    /**
     * Returns query facets.
     *
     * @return FacetInterface[]
     */
    public function getFacets();

    /**
     * Sets suggest query.
     *
     * @param Suggest $suggestQuery
     */
    public function setSuggestQuery(Suggest $suggestQuery);

    /**
     * Returns suggest query.
     *
     * @return Suggest
     */
    public function getSuggestQuery();
}