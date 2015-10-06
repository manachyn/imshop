<?php

namespace im\search\components\query;

use im\search\components\query\facet\FacetInterface;

interface QueryResultInterface
{
    /**
     * @return int
     */
    public function getTotal();

    /**
     * @return array
     */
    public function getDocuments();

    /**
     * @return array
     */
    public function getObjects();

    /**
     * @return FacetInterface[]
     */
    public function getFacets();

    /**
     * @return QueryInterface
     */
    public function getQuery();
}