<?php

namespace im\elasticsearch\components;

use im\search\components\index\Document;
use im\search\components\query\IndexQueryResultInterface;
use im\search\components\query\QueryInterface;
use ReflectionClass;
use yii\helpers\Inflector;

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

    /**
     * @var array
     */
    private $_selectedFacets = [];

    /**
     * QueryResult constructor.
     * @param QueryInterface $query
     * @param array $response
     */
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

    /**
     * Init result.
     *
     * @param array $response
     */
    protected function init(array $response)
    {
        $this->_response = $response;
        $this->total = isset($response['hits']['total']) ? $response['hits']['total'] : 0;
        $this->_maxScore = isset($response['hits']['max_score']) ? $response['hits']['max_score'] : 0;
        $this->_took = isset($response['took']) ? $response['took'] : 0;
        $this->_timedOut = !empty($response['timed_out']);
        if (isset($response['hits']['hits'])) {
            foreach ($response['hits']['hits'] as $hit) {
                $this->documents[] = new Document($hit['_id'], isset($hit['_source']) ? $hit['_source'] : [], $hit['_type'], $hit['_index'], $hit['_score']);
            }
            if (!$this->getQuery()->getOrderBy()) {
                usort($this->documents, function (Document $a, Document $b) {
                    if ($a->getRelevance() === $b->getRelevance()) {
                        return $a->getId() < $b->getId() ? -1 : 1;
                    }
                    return $a->getRelevance() > $b->getRelevance() ? -1 : 1;
                });
            }
        }
        if (isset($response['aggregations'])) {
            $aggregations = $this->parseAggregations($response['aggregations']);
            $this->parseFacets($aggregations);
        }
    }

    /**
     * Creates result facets from response.
     *
     * @param $responseFacets
     */
    private function parseFacets($responseFacets)
    {
        foreach ($this->getQuery()->getFacets() as $facet) {
            $values = $facet->getValues();
            if ($values) {
                foreach ($values as $value) {
                    $type = Inflector::camel2id((new ReflectionClass($value))->getShortName(), '_');
                    $facetName = $facet->getName() . '_' . $type;
                    if (isset($responseFacets[$facetName])) {
                        foreach ($responseFacets[$facetName]['buckets'] as $bucket) {
                            if ($bucket['key'] == $value->getKey()) {
                                $value->setResultsCount($bucket['doc_count']);
                            }
                        }
                    }
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
                $values = $facet->getValueInstances($configs);
                $facet->setValues($values);
            }
        }
    }

    /**
     * @param $aggregations
     * @return array
     */
    private function parseAggregations($aggregations)
    {
        $parsed = [];
        foreach ($aggregations as $name => $aggregation) {
            if (substr($name, -8) === 'filtered') {
                if (isset($aggregation['doc_count'])) {
                    unset($aggregation['doc_count']);
                }
                $parsed = array_merge($parsed, $this->parseAggregations($aggregation));
            } else {
                $parsed[$name] = $aggregation;
            }
        }

        return $parsed;
    }

}