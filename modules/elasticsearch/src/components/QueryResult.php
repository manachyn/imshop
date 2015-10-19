<?php

namespace im\elasticsearch\components;

use im\search\components\index\Document;
use im\search\components\query\IndexQueryResultInterface;
use im\search\components\query\QueryInterface;
use im\search\components\query\SearchQueryHelper;
use im\search\models\Facet;

/**
 * Class QueryResult
 * @package im\elasticsearch\components
 */
class QueryResult extends \im\search\components\query\QueryResult implements IndexQueryResultInterface
{
    /**
     * @var array query result documents
     */
    protected $documents = [];

    /**
     * @var array
     */
    private $_response;

    /**
     * @var float
     */
    private $_maxScore = 0;

    /**
     * @var int
     */
    private $_took = 0;

    /**
     * @var bool
     */
    private $_timedOut = false;

    private $_selectedFacets = [];

    function __construct(QueryInterface $query, array $response)
    {
        parent::__construct($query);
        $this->init($response);
    }

    /**
     * @inheritdoc
     */
    public function getObjects()
    {
        if (!$this->objects && $this->documents && $transformer = $this->getQuery()->getTransformer()) {
            $this->objects = $transformer->transform($this->documents);
        }

        return $this->objects;
    }

    /**
     * @inheritdoc
     */
    public function getSelectedFacets()
    {
        return $this->_selectedFacets;
    }

    /**
     * @inheritdoc
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    protected function init(array $response)
    {
        $this->_response = $response;
        $this->total = isset($response['hits']['total']) ? $response['hits']['total'] : 0;
        $this->_maxScore = isset($response['hits']['max_score']) ? $response['hits']['max_score'] : 0;
        $this->_took = isset($response['took']) ? $response['took'] : 0;
        $this->_timedOut = !empty($response['timed_out']);
        if (isset($response['hits']['hits'])) {
            foreach ($response['hits']['hits'] as $hit) {
                $this->documents[] = new Document($hit['_id'], $hit['_source'], $hit['_type'], $hit['_index'], $hit['_score']);
            }
        }
        if (isset($response['aggregations'])) {
            $this->parseFacets($response['aggregations']);
        }
    }

    private function parseFacets($responseFacets)
    {
        $searchQuery = $this->getQuery()->getSearchQuery();
        foreach ($this->getQuery()->getFacets() as $facet) {
            if (isset($responseFacets[$facet->getName()])) {
                $facetValues = $facet->getValues();
                $operator = $facet->getOperator() === Facet::OPERATOR_AND ? true : null;
                $selectedValues = [];
                if ($facetValues) {
                    foreach ($facetValues as $value) {
                        foreach ($responseFacets[$facet->getName()]['buckets'] as $bucket) {
                            if ($bucket['key'] == $value->getKey()) {
                                $value->setResultsCount($bucket['doc_count']);
                            }
                        }
                        SearchQueryHelper::getQueryInstanceFromFacetValue($value);
                        $valueQuery = SearchQueryHelper::getQueryInstanceFromFacetValue($value);
                        if ($searchQuery) {
                            $value->setSelected(SearchQueryHelper::isIncludeQuery($searchQuery, $valueQuery, $operator));
                            if ($value->isSelected()) {
                                $selectedValue = clone $value;
                                $selectedValueQuery = SearchQueryHelper::excludeQuery(clone $searchQuery, $valueQuery, $operator);
                                $selectedValue->setSearchQuery($selectedValueQuery);
                                $selectedValues[] = $selectedValue;
                            }
                            $valueQuery = SearchQueryHelper::includeQuery(clone $searchQuery, $valueQuery, $operator);
                        } else {
                            $value->setSelected(false);
                        }
                        $value->setSearchQuery($valueQuery);
                    }
                } else {
                    $configs = [];
                    foreach ($responseFacets[$facet->getName()]['buckets'] as $bucket) {
                        $config = [
                            'key' => $bucket['key'],
                            'resultsCount' => $bucket['doc_count']
                        ];
                        if (isset($bucket['from'])) {
                            $config['from'] = $bucket['from'];
                        }
                        if (isset($bucket['to'])) {
                            $config['to'] = $bucket['to'];
                        }
                        $configs[] = $config;
                    }
                    $facetValues = $facet->getValueInstances($configs);
                    $facet->setValues($facetValues);
                    foreach ($facetValues as $value) {
                        $valueQuery = SearchQueryHelper::getQueryInstanceFromFacetValue($value);
                        if ($searchQuery) {
                            $value->setSelected(SearchQueryHelper::isIncludeQuery($searchQuery, $valueQuery, $operator));
                            if ($value->isSelected()) {
                                $selectedValue = clone $value;
                                $selectedValueQuery = SearchQueryHelper::excludeQuery(clone $searchQuery, $valueQuery, $operator);
                                $selectedValue->setSearchQuery($selectedValueQuery);
                                $selectedValues[] = $selectedValue;
                            }
                            if (!$facet->isMultivalue()) {
                                $excludedQuery = SearchQueryHelper::excludeQueryByFieldName(clone $searchQuery, $valueQuery->getField());
                                $valueQuery = SearchQueryHelper::includeQuery($excludedQuery, $valueQuery, $operator);
                            } else {
                                $valueQuery = SearchQueryHelper::includeQuery(clone $searchQuery, $valueQuery, $operator);
                            }
                        } else {
                            $value->setSelected(false);
                        }
                        $value->setSearchQuery($valueQuery);
                    }
                }
                if ($selectedValues) {
                    $selectedFacet = clone $facet;
                    $selectedFacet->setValues($selectedValues);
                    $this->_selectedFacets[] = $selectedFacet;
                }
            }
        }
    }
}