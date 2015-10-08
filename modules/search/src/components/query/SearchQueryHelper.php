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

    public static function combineSearchQueries(SearchQueryInterface $query1, FieldQueryInterface $query2, $sign = true)
    {
        if ($query1 instanceof BooleanQueryInterface) {
            $signs = $query1->getSigns();
            $toAdd = true;
            foreach ($query1->getSubQueries() as $key => $subQuery) {
                if ($subQuery instanceof Boolean) {
                    $toAdd = false;
                    break;
                } elseif ($query2->equals($subQuery) !== 0 || $signs[$key] !== $sign) {
                    $toAdd = false;
                    break;
                } else {
                    $a = 1;
                }
            }
            if ($toAdd) {
                $query1->addSubQuery($query2, $sign);
                return true;
            } else {
                return false;
            }
        } else
        return $query1;
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