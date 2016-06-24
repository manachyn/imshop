<?php

namespace im\search\components\query\facet;

/**
 * Interface IntervalFacetInterface
 * @package im\search\components\query\facet
 */
interface IntervalFacetInterface extends FacetInterface, AutoGeneratedValuesFacetInterface
{
    /**
     * @return mixed
     */
    public function getFrom();

    /**
     * @return mixed
     */
    public function getTo();

    /**
     * @return mixed
     */
    public function getInterval();
}