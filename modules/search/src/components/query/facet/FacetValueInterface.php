<?php

namespace im\search\components\query\facet;

/**
 * Interface FacetValueInterface
 * @package im\search\components\query\facet
 */
interface FacetValueInterface
{
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
}