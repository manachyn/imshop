<?php

namespace im\search\components\query;

/**
 * Interface RangeInterface
 * @package im\search\components\query
 */
interface RangeInterface
{
    /**
     * @return mixed
     */
    public function getLowerBound();

    /**
     * @return mixed
     */
    public function getUpperBound();

    /**
     * @return bool
     */
    public function isIncludeLowerBound();

    /**
     * @return bool
     */
    public function isIncludeUpperBound();
}