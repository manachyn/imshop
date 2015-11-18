<?php

namespace im\search\components\search;

use im\search\components\index\IndexableInterface;
use im\search\components\query\BooleanQueryInterface;
use im\search\components\query\Match;
use im\search\components\query\MultiMatch;
use im\search\components\query\parser\QueryConverterInterface;
use im\search\components\query\parser\QueryParser;
use im\search\components\query\parser\QueryParserInterface;
use im\search\components\query\SearchQueryInterface;
use im\search\components\query\Term;
use im\search\components\searchable\AttributeDescriptor;
use Yii;
use yii\base\Component;

class SearchComponent extends Component
{
    /**
     * Query parser is used to parse search request to query object.
     *
     * @var QueryParserInterface
     */
    public $queryParser = [
        'class' => 'im\search\components\query\parser\QueryParser',
        'higherPriorityOperator' => QueryParser::OPERATOR_OR
    ];

    /**
     * Query converter is used to convert query back to string.
     *
     * @var QueryConverterInterface
     */
    public $queryConverter = 'im\search\components\query\parser\QueryConverter';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (!$this->queryParser instanceof QueryParserInterface) {
            $this->queryParser = Yii::createObject($this->queryParser);
        }
        if (!$this->queryConverter instanceof QueryConverterInterface) {
            $this->queryConverter= Yii::createObject($this->queryConverter);
        }
    }

    public function search($type, $params)
    {
        //$query = $this->getQuery($type);
    }

    /**
     * Return search query.
     *
     * @param string $type
     * @param SearchQueryInterface|string $searchQuery
     * @param \im\search\components\query\facet\FacetInterface[] $facets
     * @return \im\search\components\query\QueryInterface
     */
    public function getQuery($type, $searchQuery = null, $facets = [])
    {
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = Yii::$app->get('searchManager');
        $searchableType = $searchManager->getSearchableType($type);
        $searchService = $searchableType->getSearchService();
        $finder = $searchService->getFinder();
        if ($searchQuery) {
            if (is_string($searchQuery)) {
                $searchQuery = $this->parseQuery($searchQuery);
            }
            if ($searchableType instanceof IndexableInterface) {
                $fullTextSearchAttributes = array_filter($searchableType->getIndexMapping($type), function (AttributeDescriptor $attribute) {
                    return !empty($attribute->params['fullTextSearch']);
                });
                if ($fullTextSearchAttributes) {
                    $searchQuery = $this->applyFullTextSearchSettings($searchQuery, $fullTextSearchAttributes);
                }
            }
            $query = $finder->findByQuery($type, $searchQuery);
        } else {
            $query = $finder->find($type);
        }
        if ($facets) {
            foreach ($facets as $facet) {
                $query->addFacet($facet);
            }
        }

        return $query;
    }

    /**
     * Parses query string.
     *
     * @param string $querySting
     * @return \im\search\components\query\SearchQueryInterface
     */
    public function parseQuery($querySting)
    {
        $query = $this->queryParser->parse($querySting);

        return $query;
    }

    /**
     * Applies full text search settings to query.
     *
     * @param SearchQueryInterface $searchQuery
     * @param AttributeDescriptor[] $fullTextSearchAttributes
     * @param string $fullTextSearchParam
     * @return SearchQueryInterface
     */
    public function applyFullTextSearchSettings(SearchQueryInterface $searchQuery, $fullTextSearchAttributes, $fullTextSearchParam = 'text')
    {
        if ($fullTextSearchAttributes) {
            if ($searchQuery instanceof BooleanQueryInterface) {
                $subQueries = $searchQuery->getSubQueries();
                $signs = $searchQuery->getSigns();
                foreach ($subQueries as $key => $subQuery) {
                    $subQueries[$key] = $this->applyFullTextSearchSettings($subQuery, $fullTextSearchAttributes, $fullTextSearchParam);
                }
                $searchQuery->setSubQueries($subQueries, $signs);
            } elseif ($searchQuery instanceof Term) {
                $field = $searchQuery->getField();
                if ($field == $fullTextSearchParam) {
                    $fields = array_map(function (AttributeDescriptor $attribute) {
                        return $attribute->name;
                    }, $fullTextSearchAttributes);
                    $searchQuery = new MultiMatch($fields, $searchQuery);
                } elseif (array_filter($fullTextSearchAttributes, function (AttributeDescriptor $attribute) use ($field) { return $attribute->name == $field; })) {
                    $searchQuery = new Match($searchQuery->getField(), $searchQuery);
                }
            }
        }

        return $searchQuery;
    }
}