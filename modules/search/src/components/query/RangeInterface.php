<?php

namespace im\search\components\query;

interface RangeInterface extends FacetValueInterface
{
    /**
     * @return mixed
     */
    public function getFrom();

    /**
     * @return mixed
     */
    public function getTo();
}