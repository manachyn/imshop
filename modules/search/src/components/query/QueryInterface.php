<?php

namespace im\search\components\query;

use im\search\components\transformer\DocumentToObjectTransformerInterface;
use yii\db\Connection;

interface QueryInterface extends \yii\db\QueryInterface
{
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

    /**
     * @return DocumentToObjectTransformerInterface
     */
    public function getTransformer();

    /**
     * @param DocumentToObjectTransformerInterface $transformer
     */
    public function setTransformer(DocumentToObjectTransformerInterface $transformer);
}