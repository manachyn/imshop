<?php

namespace im\search\components\query\facet;

/**
 * Interface RangeFacetInterface
 * @package im\search\components\query\facet
 */
interface RangeFacetInterface extends FacetInterface
{
//    /**
//     * @return RangeFacetValueInterface[]
//     */
//    public function getRanges();

    /**
     * @return FacetValueInterface[]
     */
    public function getRanges();
}