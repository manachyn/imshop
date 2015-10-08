<?php

namespace im\search\components\query\facet;
use im\search\components\query\SearchQueryInterface;

/**
 * Interface FacetValueInterface
 * @package im\search\components\query\facet
 */
interface FacetValueInterface
{
    /**
     * Returns facet.
     *
     * @return FacetInterface
     */
    public function getFacet();

    /**
     * Sets facet.
     *
     * @param FacetInterface $facet
     */
    public function setFacet(FacetInterface $facet);

    /**
     * Return facet value key.
     *
     * @return string
     */
    public function getKey();

    /**
     * Sets facet value key.
     *
     * @param $key
     */
    public function setKey($key);

    /**
     * Return results count for facet value.
     *
     * @return int
     */
    public function getResultsCount();

    /**
     * Sets results count of facet value.
     *
     * @param int $count
     */
    public function setResultsCount($count);

    /**
     * Return facet value label.
     *
     * @return string
     */
    public function getLabel();

    /**
     * Returns search query for facet value.
     *
     * @return SearchQueryInterface
     */
    public function getSearchQuery();

    /**
     * Sets search query for facet value.
     *
     * @param SearchQueryInterface $searchQuery
     */
    public function setSearchQuery(SearchQueryInterface $searchQuery);
}