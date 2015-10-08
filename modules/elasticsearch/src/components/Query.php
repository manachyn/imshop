<?php

namespace im\elasticsearch\components;

use im\search\components\query\Boolean;
use im\search\components\query\facet\FacetInterface;
use im\search\components\query\facet\IntervalFacetInterface;
use im\search\components\query\facet\RangeFacetInterface;
use im\search\components\query\QueryInterface;
use im\search\components\query\QueryResultInterface;
use im\search\components\query\Range;
use im\search\components\query\SearchQueryInterface;
use im\search\components\query\Term;
use im\search\components\transformer\DocumentToObjectTransformerInterface;

/**
 * Class Query
 * @package im\elasticsearch\components
 */
class Query extends \yii\elasticsearch\Query implements QueryInterface
{
    /**
     * @var DocumentToObjectTransformerInterface
     */
    private $_transformer;

    /**
     * @var FacetInterface[]
     */
    private $_facets;

    /**
     * @var QueryResultInterface
     */
    private $_result;

    /**
     * @var SearchQueryInterface
     */
    private $_searchQuery;

    /**
     * @inheritdoc
     */
    public function result($db = null)
    {
        if (!$this->_result) {
            $response = $this->createCommand($db)->search();
            $this->_result = new QueryResult($this, $response);
        }

        return $this->_result;
    }

    /**
     * @inheritdoc
     */
    public function addFacet(FacetInterface $facet)
    {
        $this->_facets[$facet->getName()] = $facet;
        if ($facet instanceof RangeFacetInterface) {
            $this->addRangeFacet($facet);
        } elseif ($facet instanceof IntervalFacetInterface) {
            $this->addIntervalFacet($facet);
        } else {
            $this->addTermsFacet($facet);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getFacets()
    {
        return $this->_facets;
    }

    /**
     * @inheritdoc
     */
    public function getTransformer()
    {
        return $this->_transformer;
    }

    /**
     * @inheritdoc
     */
    public function setTransformer(DocumentToObjectTransformerInterface $transformer)
    {
        $this->_transformer = $transformer;
    }

    /**
     * @inheritdoc
     */
    public function getSearchQuery()
    {
        return $this->_searchQuery;
    }

    /**
     * @inheritdoc
     */
    public function setSearchQuery(SearchQueryInterface $searchQuery)
    {
        $this->_searchQuery = $searchQuery;
        $this->query = $this->mapQuery($searchQuery);
    }

    /**
     * @param SearchQueryInterface $query
     * @return array
     */
    protected function mapQuery(SearchQueryInterface $query)
    {
        $queryArr = [];
        if ($query instanceof Boolean) {
            $signs = $query->getSigns();
            $boolean = [];
            foreach ($query->getSubQueries() as $key => $subQuery) {
                $sign = isset($signs[$key]) ? $signs[$key] : null;
                switch ($sign) {
                    case true:
                        if ($subQueryArr = $this->mapQuery($subQuery)) {
                            if (!isset($boolean['must'])) {
                                $boolean['must'] = [];
                            }
                            $boolean['must'][] = $this->mapQuery($subQuery);
                        }
                        break;
                    case false:
                        if ($subQueryArr = $this->mapQuery($subQuery)) {
                            if (!isset($booleanr['must_not'])) {
                                $boolean['must_not'] = [];
                            }
                            $boolean['must_not'][] = $this->mapQuery($subQuery);
                        }
                        break;
                    case null:
                        if ($subQueryArr = $this->mapQuery($subQuery)) {
                            if (!isset($boolean['should'])) {
                                $boolean['should'] = [];
                            }
                            $boolean['should'][] = $this->mapQuery($subQuery);
                        }
                        break;
                }
            }
            if ($boolean) {
                $queryArr['bool'] = $boolean;
            }
        } elseif ($query instanceof Term) {
            $queryArr['term'] = [$query->getField() => $query->getTerm()];
        } elseif ($query instanceof Range) {
            $range = [];
            if (($from = $query->getLowerBound()) !== null) {
                $range[$query->isIncludeLowerBound() ? 'gte' : 'gt'] = $from;
            }
            if (($to = $query->getUpperBound()) !== null) {
                $range[$query->isIncludeUpperBound() ? 'lte' : 'lt'] = $to;
            }
            $queryArr['range'] = [$query->getField() => $range];
        }

        return $queryArr;
    }

    protected function addRangeFacet(RangeFacetInterface $facet)
    {
        $ranges = $facet->getRanges();
        if ($ranges) {
            $options = ['field' => $facet->getField(), 'ranges' => []];
            foreach ($ranges as $range) {
                $optionsRange = [];
                if ($from = $range->getLowerBound()) {
                    $optionsRange['from'] = $from;
                }
                if ($to = $range->getUpperBound()) {
                    $optionsRange['to'] = $to;
                }
                if ($optionsRange) {
                    $optionsRange['key'] = $range->getKey();
                    $options['ranges'][] = $optionsRange;
                }
            }
            if ($options['ranges']) {
                $this->addAggregation($facet->getName(), 'range', $options);
            }
        }
    }

    protected function addIntervalFacet(IntervalFacetInterface $facet)
    {
        $options = ['field' => $facet->getField(), 'interval' => $facet->getInterval(), 'min_doc_count' => 0, 'extended_bounds' => []];
//        if ($from = $facet->getFrom()) {
//            $options['extended_bounds']['min'] = 1000;
//        }
//        if ($to = $facet->getTo()) {
//            //$options['extended_bounds']['max'] = 50;
//        }
        if (!$options['extended_bounds']) {
            unset($options['extended_bounds']);
        }
        $this->addAggregation($facet->getName(), 'histogram', $options);
    }

    protected function addTermsFacet(FacetInterface $facet)
    {
        $options = ['field' => $facet->getField()];
        $this->addAggregation($facet->getName(), 'terms', $options);
    }
}