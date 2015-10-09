<?php

namespace im\search\components\query;

use im\search\components\query\facet\FacetValueInterface;
use im\search\models\FacetInterval;
use im\search\models\FacetTerm;
use im\search\models\IntervalFacet;

class SearchQueryHelper
{
    public static function mergeQueries()
    {

    }

    /**
     * Include one query to another.
     *
     * @param SearchQueryInterface $toQuery
     * @param FieldQueryInterface $query
     * @param bool|null $sign
     * @param bool|null $topLevelSign
     * @return SearchQueryInterface
     */
    public static function includeQuery(SearchQueryInterface $toQuery, FieldQueryInterface $query, $sign = true, $topLevelSign = true)
    {
        if ($newQuery = self::addQuery($toQuery, $query, $sign)) {
            return $newQuery;
        } else {
            if ($toQuery instanceof BooleanQueryInterface) {
                $signs = $toQuery->getSigns();
                $sameSigns = true;
                foreach ($signs as $sign) {
                    if ($sign !== $topLevelSign) {
                        $sameSigns = false;
                        break;
                    }
                }
                if ($sameSigns) {
                    $toQuery->addSubQuery($query, $topLevelSign);
                    return $toQuery;
                }
            }
            $newQuery = new Boolean();
            $newQuery->setSubQueries([$toQuery, $query], [$sign, $sign]);
        }

        return $newQuery;
    }

    public static function excludeQuery(SearchQueryInterface $fromQuery, FieldQueryInterface $query, $sign = true)
    {
        if ($newQuery = self::addQuery($fromQuery, $query, $sign)) {
            return $newQuery;
        } else {
            $newQuery = new Boolean();
            $newQuery->setSubQueries([$fromQuery, $query], [$sign, $sign]);
        }

        return $newQuery;
    }

    /**
     * Add one query to another.
     *
     * @param SearchQueryInterface $toQuery
     * @param FieldQueryInterface $query
     * @param bool|null $sign
     * @return bool|SearchQueryInterface
     */
    protected static function addQuery(SearchQueryInterface $toQuery, FieldQueryInterface $query, $sign = true)
    {
        if ($toQuery instanceof BooleanQueryInterface) {
            $subQueries = $toQuery->getSubQueries();
            $signs = $toQuery->getSigns();
            foreach ($subQueries as $key => $subQuery) {
                if ($newQuery = self::addQuery($subQuery, $query, $sign)) {
                    $subQueries[$key] = $newQuery;
                    $toQuery->setSubQueries($subQueries, $signs);
                    return $toQuery;
                }
            }
        } elseif ($toQuery instanceof FieldQueryInterface) {
            $equal = $toQuery->equals($query);
            if ($equal === 0) {
                $newQuery = new Boolean();
                $newQuery->setSubQueries([$toQuery, $query], [$sign, $sign]);
                return $newQuery;
            } elseif ($equal === 1) {
                return $toQuery;
            }
        }

        return false;
    }

    protected static function removeQuery(SearchQueryInterface $fromQuery, FieldQueryInterface $query, $sign = true)
    {
        if ($fromQuery instanceof BooleanQueryInterface) {
            $subQueries = $fromQuery->getSubQueries();
            $signs = $fromQuery->getSigns();
            foreach ($subQueries as $key => $subQuery) {
                if ($subQuery instanceof FieldQueryInterface && $signs[$key] === $sign && $query->equals($fromQuery) === 1) {
                    unset($subQueries[$key], $signs[$key]);
                }
            }
            $fromQuery->setSubQueries($subQueries, $signs);
        } elseif ($fromQuery instanceof FieldQueryInterface && $query->equals($fromQuery) === 1) {
            return new Boolean();
        }

        return $fromQuery;
    }

    /**
     * @param FacetValueInterface $facetValue
     * @return FieldQueryInterface
     */
    public static function getQueryInstanceFromFacetValue(FacetValueInterface $facetValue)
    {
        $query = null;
        $facet = $facetValue->getFacet();
        if ($facetValue instanceof FacetTerm) {
            $query = new Term($facet->getName(), $facetValue->getKey());
        } elseif ($facetValue instanceof RangeInterface) {
            $query = new Range($facet->getName(), $facetValue->getLowerBound(), $facetValue->getUpperBound(), $facetValue->isIncludeLowerBound(), $facetValue->isIncludeUpperBound());
        } elseif ($facetValue instanceof FacetInterval) {
            /** @var IntervalFacet $facet */
            /** @var int|float $key */
            $key = $facetValue->getKey();
            $query = new Range($facet->getName(), $facetValue->getKey(), $key + $facet->interval, true, false);
        }

        return $query;
    }
}