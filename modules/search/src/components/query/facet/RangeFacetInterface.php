<?php

namespace im\search\components\query\facet;

use im\search\components\query\RangeInterface;

/**
 * Interface RangeFacetInterface
 * @package im\search\components\query\facet
 */
interface RangeFacetInterface extends FacetInterface
{
    /**
     * @return RangeFacetValueInterface[]
     */
    public function getRanges();
}