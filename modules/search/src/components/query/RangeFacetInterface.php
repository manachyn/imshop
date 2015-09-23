<?php

namespace im\search\components\query;

interface RangeFacetInterface extends FacetInterface
{
    /**
     * @return RangeInterface[]
     */
    public function getRanges();
}