<?php

namespace im\search\components\query;

use im\search\components\query\facet\FacetInterface;
use yii\db\Connection;

interface QueryInterface extends \yii\db\QueryInterface
{
    /**
     * Sets search query.
     *
     * @param SearchQueryInterface $searchQuery
     */
    public function setSearchQuery($searchQuery);

    /**
     * Returns search query.
     *
     * @return SearchQueryInterface
     */
    public function getSearchQuery();

    /**
     * Executes the query and returns result object.
     *
     * @param Connection $db the service connection used to execute the query.
     * @return QueryResultInterface
     */
    public function result($db = null);

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
}