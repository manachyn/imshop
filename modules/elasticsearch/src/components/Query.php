<?php

namespace im\elasticsearch\components;

use im\search\components\query\Boolean;
use im\search\components\query\BooleanQueryInterface;
use im\search\components\query\facet\FacetInterface;
use im\search\components\query\facet\FacetValueInterface;
use im\search\components\query\facet\IntervalFacetInterface;
use im\search\components\query\facet\RangeFacetInterface;
use im\search\components\query\facet\RangeFacetValueInterface;
use im\search\components\query\Match;
use im\search\components\query\MultiMatch;
use im\search\components\query\QueryInterface;
use im\search\components\query\QueryResultInterface;
use im\search\components\query\Range;
use im\search\components\query\SearchQueryInterface;
use im\search\components\query\Term;
use im\search\components\transformer\DocumentToObjectTransformerInterface;
use ReflectionClass;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

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
     * @var SearchQueryInterface
     */
    private $_facetsFilter;

    /**
     * @inheritdoc
     */
    public function createCommand($db = null)
    {
        $this->normalizeFacets();
        $searchQuery = $this->getSearchQuery();

        if ($searchQuery && $this->_facetsFilter && $searchQuery === $this->_facetsFilter) {
            $this->query = ['filtered' => ['filter' => $this->mapQuery($searchQuery)]];
        } elseif ($searchQuery && $this->_facetsFilter) {
            $this->filter = $this->mapQuery($searchQuery);
            $this->aggregations = ['aggregations_filtered' => ['filter' => $this->mapQuery($this->_facetsFilter), 'aggs' => $this->aggregations]];
        } elseif ($searchQuery) {
            //$this->filter = $this->mapQuery($searchQuery);
//            $this->query = ['filtered' => ['filter' => $this->mapQuery($searchQuery), 'query' => ['bool' => [
//                'should' => [
//                    ['match' => ['title' => 'Test']]
//                ]
//            ]]]];
//            $this->query = ['filtered' => [
//                'filter' => [
//                    'bool' => ['should' => [
//                        ['term' => ['status' => 1]]
//                    ]]
//                ],
//                'query' => [
//                    'bool' => ['should' => [
//                        ['match' => ['title' => 'Test']]
//                    ]]
//                ]
//            ]];
//            $this->filter = ['bool' => [
//                'should' => [
//                    ['term' => ['status' => 1]],
//                    ['query' => ['match' => ['title' => 'Test']]]
//                ]
//            ]];
//            $this->query = ['filtered' => ['filter' => ['bool' => [
//                'should' => [
//                    ['term' => ['status' => 1]],
//                    ['query' => ['match' => ['title' => 'Test']]]
//                ]
//            ]]]];

//            $this->query = ['match' => ['title' => 'Test']];

//            $this->query = ['bool' => [
//                'should' => [
//                    ['match' => ['title' => 'Test']],
//                    ['term' => ['status' => 1]]
//                ]
//            ]];

            //$this->aggregations = ['all' => ['global' => new \stdClass(), 'aggs' => $this->aggregations]];

            $this->query = $this->mapQuery($searchQuery);

            //$this->query = ['filtered' => ['filter' => $this->mapQuery($searchQuery)]];
        }

        return parent::createCommand($db);
    }

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
    public function setSearchQuery($searchQuery)
    {
        $this->_searchQuery = $searchQuery;
    }

    /**
     * @param SearchQueryInterface $query
     * @param bool $asFilter
     * @return array
     */
    protected function mapQuery(SearchQueryInterface $query, $asFilter = false)
    {
        $queryArr = [];
        if ($query instanceof Boolean) {
            $signs = $query->getSigns();
            $boolean = [];
            foreach ($query->getSubQueries() as $key => $subQuery) {
                $sign = isset($signs[$key]) ? $signs[$key] : null;
                if ($sign === true) {
                    if ($subQueryArr = $this->mapQuery($subQuery, $asFilter)) {
                        if (!isset($boolean['must'])) {
                            $boolean['must'] = [];
                        }
                        $boolean['must'][] = $this->mapQuery($subQuery, $asFilter);
                    }
                } elseif ($sign === false) {
                    if ($subQueryArr = $this->mapQuery($subQuery, $asFilter)) {
                        if (!isset($booleanr['must_not'])) {
                            $boolean['must_not'] = [];
                        }
                        $boolean['must_not'][] = $this->mapQuery($subQuery, $asFilter);
                    }
                } elseif ($sign === null) {
                    if ($subQueryArr = $this->mapQuery($subQuery, $asFilter)) {
                        if (!isset($boolean['should'])) {
                            $boolean['should'] = [];
                        }
                        $boolean['should'][] = $this->mapQuery($subQuery, $asFilter);
                    }
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
        } elseif ($query instanceof Match) {
            if ($asFilter) {
                $queryArr['query']['match'] = [$query->getField() => $query->getTerm()->getTerm()];
            } else {
                $queryArr['match'] = [$query->getField() => $query->getTerm()->getTerm()];
            }
        } elseif ($query instanceof MultiMatch) {
            $queryArr['multi_match'] = [
                'query' => $query->getTerm()->getTerm(),
                'type' => 'most_fields',
                'fields' => $query->getFields()
            ];
        }

        return $queryArr;
    }

    protected function normalizeFacets()
    {
        if ($facets = $this->getFacets()) {
            //$this->_facetsFilter = $this->getFacetsCommonFilter($facets);
            $this->_facetsFilter = null;
            if ($this->_facetsFilter) {
                foreach ($facets as $facet) {
                    $this->addFacetAggregation($this->getAggregationConfig($facet));
                }
            } else {
                $facetsByFilter = [];
                $facetsWithoutFilter = [];
                foreach ($facets as $facet) {
                    $filter = $facet->getFilter();
                    if ($filter) {
                        $filterHash = spl_object_hash($filter);
                        if (!isset($facetsByFilter[$filterHash])) {
                            $facetsByFilter[$filterHash] = ['facets' => []];
                        }
                        $facetsByFilter[$filterHash]['filter'] = $filter;
                        $facetsByFilter[$filterHash]['facets'][] = $facet;
                    } else {
                        $facetsWithoutFilter[] = $facet;
                    }
                }
                $filteredAggregations = [];
                foreach ($facetsByFilter as $key => $item) {
                    $aggregationsConfig = [];
                    foreach ($item['facets'] as $facet) {
                        $facetAggregationConfig = $this->getAggregationConfig($facet);
                        $aggregationsConfig = array_merge($aggregationsConfig, ArrayHelper::isAssociative($facetAggregationConfig) ? [$facetAggregationConfig] : $facetAggregationConfig);
                    }
                    /** @var SearchQueryInterface $filter */
                    $filter = $item['filter'];
                    if (!$filter->isEmpty()) {
                        $aggregationsConfig = [
                            'name' => $key . '_filtered',
                            'options' => [
                                'filter' => $this->mapQuery($item['filter'], true),
                                'aggs' => ArrayHelper::map($aggregationsConfig, function ($agg) {
                                    return $agg['name'];
                                }, function ($agg) {
                                    return [$agg['type'] => $agg['options']];
                                })
                            ]
                        ];
                        $filteredAggregations[$aggregationsConfig['name']] = $aggregationsConfig['options'];
                    }
                    //$this->addFacetAggregation($aggregationsConfig);
                }
                $this->addFacetAggregation(['name' => 'all_filtered', 'options' => ['global' => new \stdClass(), 'aggs' => $filteredAggregations]]);
                foreach ($facetsWithoutFilter as $facet) {
                    $this->addFacetAggregation($this->getAggregationConfig($facet));
                }
            }
        }
    }

    /**
     * @param FacetInterface[] $facets
     * @return SearchQueryInterface|null
     */
    protected function getFacetsCommonFilter($facets)
    {
        $commonFilter = null;
        foreach ($facets as $facet) {
            if (!$commonFilter) {
                $commonFilter = $facet->getFilter();
            } elseif ($facet->getFilter() !== $commonFilter) {
                $commonFilter = null;
                break;
            }
        }

        return $commonFilter;
    }

    /**
     * @param FacetInterface $facet
     * @return array
     */
    protected function getAggregationConfig(FacetInterface $facet)
    {
        $aggregation = [];
        $options = ['field' => $facet->getField()];
        $values = $facet->getValues();
        if ($values) {
            $valuesByType = [];
            foreach ($values as $value) {
                $valuesByType[Inflector::camel2id((new ReflectionClass($value))->getShortName(), '_')][] = $value;
            }
            foreach ($valuesByType as $type => $values) {
                $value = $values[0];
                if ($value instanceof RangeFacetValueInterface) {
                    $aggregationOptions = $this->getRangeAggregationOptions($values);
                    $aggregationType = 'range';
                } else {
                    $aggregationOptions = $this->getTermsAggregationOptions($values);
                    $aggregationType = 'terms';
                }
                $aggregation[] = [
                    'name' => $facet->getName() . '_' . $type,
                    'type' => $aggregationType,
                    'options' => array_merge($options, $aggregationOptions)
                ];
            }
        } elseif ($facet instanceof IntervalFacetInterface) {
            $aggregation = [
                'name' => $facet->getName(),
                'type' => 'histogram',
                'options' => array_merge($options, $this->getIntervalAggregationOptions($facet))
            ];
        } else {
            $aggregation = [
                'name' => $facet->getName(),
                'type' => 'terms',
                'options' => array_merge($options, $this->getTermsAggregationOptions())
            ];
        }

        return $aggregation;
    }

    /**
     * @param array $aggregation
     */
    public function addFacetAggregation($aggregation)
    {
        if ($aggregation) {
            $aggregations = ArrayHelper::isAssociative($aggregation) ? [$aggregation] : $aggregation;
            foreach ($aggregations as $aggregation) {
                if (isset($aggregation['type'])) {
                    $this->addAggregation($aggregation['name'], $aggregation['type'], $aggregation['options']);
                } else {
                    $this->aggregations = array_merge($this->aggregations, [$aggregation['name'] => $aggregation['options']]);
                }
            }
        }
    }

    /**
     * @param RangeFacetValueInterface[] $values
     * @return array
     */
    protected function getRangeAggregationOptions($values = [])
    {
        $options = ['ranges' => []];
        if ($values) {
            foreach ($values as $range) {
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
        }

        return $options;
    }

    protected function getIntervalAggregationOptions(IntervalFacetInterface $facet)
    {
        return ['interval' => $facet->getInterval(), 'min_doc_count' => 0];
    }

    /**
     * @param FacetValueInterface[] $values
     * @return array
     */
    protected function getTermsAggregationOptions($values = [])
    {
        $options = ['min_doc_count' => 0];
        if ($values) {
            $options['include'] = array_map(function (FacetValueInterface $value) {
                return $value->getKey();
            }, $values);
        }

        return $options;
    }

    protected function extractQuery(SearchQueryInterface $searchQuery)
    {
        if ($searchQuery instanceof BooleanQueryInterface) {
            $subQueries = $searchQuery->getSubQueries();
            $signs = $searchQuery->getSigns();
            foreach ($subQueries as $key => $subQuery) {

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
    }
}