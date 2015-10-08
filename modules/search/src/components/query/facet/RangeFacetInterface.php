<?php

namespace im\search\components\query\facet;

/**
 * Interface RangeFacetInterface
 * @package im\search\components\query\facet
 */
interface RangeFacetInterface extends FacetInterface
{
    /**
     * @return FacetValueInterface[]
     */
    public function getRanges();
}