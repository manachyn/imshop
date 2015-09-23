<?php

namespace im\search\components\query;

interface IntervalFacetInterface extends FacetInterface
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