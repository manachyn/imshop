<?php

namespace im\search\components\search;

use im\search\components\index\IndexableInterface;
use im\search\components\query\BooleanQueryInterface;
use im\search\components\query\Match;
use im\search\components\query\MultiMatch;
use im\search\components\query\parser\QueryConverterInterface;
use im\search\components\query\parser\QueryParser;
use im\search\components\query\parser\QueryParserContextInterface;
use im\search\components\query\parser\QueryParserInterface;
use im\search\components\query\SearchQueryInterface;
use im\search\components\query\Term;
use im\search\components\searchable\AttributeDescriptor;
use im\search\components\searchable\SearchableInterface;
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
     * @param SearchableInterface|string $searchableType
     * @param SearchQueryInterface|string $searchQuery
     * @param \im\search\components\query\facet\FacetInterface[] $facets
     * @return \im\search\components\query\QueryInterface
     */
    public function getQuery($searchableType, $searchQuery = null, $facets = [])
    {
        if (is_string($searchableType)) {
            /** @var \im\search\components\SearchManager $searchManager */
            $searchManager = Yii::$app->get('searchManager');
            $searchableType = $searchManager->getSearchableType($searchableType);
        }
        $searchService = $searchableType->getSearchService();
        $finder = $searchService->getFinder();
        if ($searchQuery) {
            if (is_string($searchQuery)) {
                $searchQuery = $this->parseQuery($searchQuery);
            }
            $fullTextSearchAttributes = $searchableType->getFullTextSearchAttributes();
            if ($fullTextSearchAttributes) {
                $searchQuery = $this->applyFullTextSearchSettings($searchQuery, $fullTextSearchAttributes);
            }
            $query = $finder->findByQuery($searchableType->getType(), $searchQuery);
        } else {
            $query = $finder->find($searchableType->getType());
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
     * @param QueryParserContextInterface $context
     * @return SearchQueryInterface
     */
    public function parseQuery($querySting, QueryParserContextInterface $context = null)
    {
        $query = $this->queryParser->parse($querySting, $context);

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