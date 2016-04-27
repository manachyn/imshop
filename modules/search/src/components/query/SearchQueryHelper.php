<?php

namespace im\search\components\query;

use im\search\components\query\facet\FacetValueInterface;
use im\search\components\query\facet\IntervalFacetInterface;
use im\search\components\query\facet\IntervalFacetValueInterface;
use im\search\components\query\facet\RangeFacetValueInterface;

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
                foreach ($signs as $toQuerySign) {
                    if ($toQuerySign !== $topLevelSign) {
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
            $newQuery->setSubQueries([$toQuery, $query], [$topLevelSign, $topLevelSign]);
        }

        return $newQuery;
    }

    public static function isIncludeQuery(SearchQueryInterface $inQuery, FieldQueryInterface $query, $sign = true)
    {
        if ($inQuery instanceof BooleanQueryInterface) {
            $signs = $inQuery->getSigns();
            foreach ($inQuery->getSubQueries() as $key => $subQuery) {
                if ($signs[$key] === $sign && self::isIncludeQuery($subQuery, $query, $sign)) {
                    return true;
                }
            }
        } elseif ($inQuery instanceof FieldQueryInterface && $query->equals($inQuery) === 1) {
            return true;
        }

        return false;
    }

    public static function excludeQuery(SearchQueryInterface $fromQuery, FieldQueryInterface $query, $sign = true)
    {
        $query = self::removeQuery($fromQuery, $query, $sign);
        if ($query instanceof BooleanQueryInterface && count($query->getSubQueries()) === 1) {
            $query = reset($query->getSubQueries());
        }

        return $query;
    }

    public static function excludeQueryByFieldName(SearchQueryInterface $fromQuery, $field, $sign = true)
    {
        $query = self::removeQueryByFieldName($fromQuery, $field, $sign);
        if ($query instanceof BooleanQueryInterface && count($query->getSubQueries()) === 1) {
            $query = reset($query->getSubQueries());
        }

        return $query;
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
            $removed = false;
            foreach ($subQueries as $key => $subQuery) {
                if ($subQuery instanceof FieldQueryInterface) {
                    if ($signs[$key] === $sign && $query->equals($subQuery) === 1) {
                        unset($subQueries[$key], $signs[$key]);
                        $removed = true;
                    }
                }
            }
            if (!$removed) {
                foreach ($subQueries as $key => $subQuery) {
                    if ($subQuery instanceof BooleanQueryInterface) {
                        $subQueries[$key] = self::removeQuery($subQuery, $query, $sign);
                    }
                }
            }
            $fromQuery->setSubQueries($subQueries, $signs);
        } elseif ($fromQuery instanceof FieldQueryInterface && $query->equals($fromQuery) === 1) {
            return new Boolean();
        }

        return $fromQuery;
    }

    protected static function removeQueryByFieldName(SearchQueryInterface $fromQuery, $field, $sign = true)
    {
        if ($fromQuery instanceof BooleanQueryInterface) {
            $subQueries = $fromQuery->getSubQueries();
            $signs = $fromQuery->getSigns();
            $removed = false;
            foreach ($subQueries as $key => $subQuery) {
                if ($subQuery instanceof FieldQueryInterface) {
                    if ($signs[$key] === $sign && $subQuery->getField() === $field) {
                        unset($subQueries[$key], $signs[$key]);
                        $removed = true;
                    }
                }
            }
            if (!$removed) {
                foreach ($subQueries as $key => $subQuery) {
                    if ($subQuery instanceof BooleanQueryInterface) {
                        $subQueries[$key] = self::removeQueryByFieldName($subQuery, $field, $sign);
                    }
                }
            }
            $fromQuery->setSubQueries($subQueries, $signs);
        } elseif ($fromQuery instanceof FieldQueryInterface && $fromQuery->getField() === $field) {
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
        if ($facetValue instanceof RangeFacetValueInterface) {
            $query = new Range($facet->getName(), $facetValue->getLowerBound(), $facetValue->getUpperBound(), $facetValue->isIncludeLowerBound(), $facetValue->isIncludeUpperBound());
        } elseif ($facetValue instanceof IntervalFacetValueInterface) {
            /** @var IntervalFacetInterface $facet */
            /** @var int|float $key */
            $key = $facetValue->getKey();
            $query = new Range($facet->getName(), $facetValue->getKey(), $key + $facet->getInterval(), true, false);
        } else {
            $query = new Term($facet->getName(), $facetValue->getKey());
        }

        return $query;
    }
}