<?php

namespace im\search\components\search;

use im\search\components\index\IndexableInterface;
use im\search\components\query\BooleanQueryInterface;
use im\search\components\query\facet\FacetInterface;
use im\search\components\query\Match;
use im\search\components\query\MultiMatch;
use im\search\components\query\parser\QueryConverterInterface;
use im\search\components\query\parser\QueryParser;
use im\search\components\query\parser\QueryParserContextInterface;
use im\search\components\query\parser\QueryParserInterface;
use im\search\components\query\QueryInterface;
use im\search\components\query\SearchQueryInterface;
use im\search\components\query\Suggest;
use im\search\components\query\SuggestionsQueryInterface;
use im\search\components\query\Term;
use im\search\components\searchable\AttributeDescriptor;
use im\search\components\searchable\SearchableInterface;
use im\search\components\SearchBehavior;
use im\search\models\FacetSet;
use Yii;
use yii\base\Component;
use yii\base\Model;

/**
 * Class SearchComponent
 * @package im\search\components\search
 */
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
     * @var \im\search\components\SearchManager
     */
    private $_searchManager;

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
    /**
     * Returns data provider for searchable type.
     *
     * @param SearchableInterface|string $searchableType
     * @param SearchQueryInterface|string $searchQuery
     * @param \im\search\components\query\facet\FacetInterface[] $facets
     * @param Model $model
     * @param array $params
     * @return SearchDataProvider
     */
    public function getSearchDataProvider($searchableType, $searchQuery = null, $facets = [], Model $model = null, array $params = [])
    {
        $searchableType = $this->normalizeSearchableType($searchableType);
        $query = $this->getQuery($searchableType, $searchQuery, $facets, $model, $params);
        $dataProvider = new SearchDataProvider([
            'query' => $query
        ]);

        return $dataProvider;
    }

    /**
     * Return search query.
     *
     * @param string $searchableType
     * @param SearchQueryInterface|string $searchQuery
     * @param FacetInterface[] $facets
     * @param Model $model
     * @param array $params
     * @return QueryInterface
     */
    public function getQuery($searchableType, $searchQuery = null, $facets = [], Model $model = null, $params = [])
    {
        $searchableType = $this->normalizeSearchableType($searchableType);
        if ($searchQuery) {
            $searchQuery = $this->normalizeSearchableQuery($searchQuery, $searchableType);
        }
        $searchService = $searchableType->getSearchService();
        $finder = $searchService->getFinder();
        if ($searchQuery) {
            $query = $finder->findByQuery($searchableType->getType(), $searchQuery);
        } else {
            $query = $finder->find($searchableType->getType());
        }
        if ($facets) {
            foreach ($facets as $facet) {
                $facet->setContext($model);
            }
            $query->setFacets($facets);
        }

        return $query;
    }

    /**
     * Return suggestions query.
     *
     * @param string $text
     * @param SearchableInterface|string $searchableType
     * @param SearchQueryInterface|string $searchQuery
     * @return SuggestionsQueryInterface
     */
    public function getSuggestionsQuery($text, $searchableType, $searchQuery = null)
    {
        $searchableType = $this->normalizeSearchableType($searchableType);
        if ($searchQuery) {
            $searchQuery = $this->normalizeSearchableQuery($searchQuery, $searchableType);
        }
        $searchService = $searchableType->getSearchService();
        $finder = $searchService->getFinder();
        $query = $finder->findSuggestions($this->getSuggestQuery($text, $searchableType), $searchableType->getType(), $searchQuery);

        return $query;
    }


    /**
     * Returns model facets.
     *
     * @param Model $model
     * @param SearchableInterface|string|null $searchableType
     * @return \im\search\components\query\facet\FacetInterface[]
     */
    public function getFacets(Model $model, $searchableType = null)
    {
        $facets = [];
        if ($model && $model->getBehavior('search')) {
            /** @var SearchBehavior|Model $model */
            $facets = $model->getFacets();
        }
        if ($facets && ($searchableType = $this->normalizeSearchableType($searchableType))) {
            //TODO Delete unsupported facets for searchable type
        }

        return $facets;
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
        return $this->queryParser->parse($querySting, $context);
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

    /**
     * @param SearchableInterface|string $searchableType
     * @return SearchableInterface
     */
    protected function normalizeSearchableType($searchableType)
    {
        if (is_string($searchableType)) {
            $searchableType = $this->getSearchManager()->getSearchableType($searchableType);
        }

        return $searchableType;
    }

    /**
     * @param SearchQueryInterface|string $searchQuery
     * @param SearchableInterface $searchableType
     * @return SearchQueryInterface
     */
    protected function normalizeSearchableQuery($searchQuery, $searchableType = null)
    {
        if (is_string($searchQuery)) {
            if ($searchableType instanceof QueryParserContextInterface) {
                $searchQuery = $this->parseQuery($searchQuery, $searchableType);
            } else {
                $searchQuery = $this->parseQuery($searchQuery);
            }
        }
        if ($searchableType) {
            $fullTextSearchAttributes = $searchableType->getFullTextSearchAttributes();
            if ($fullTextSearchAttributes) {
                $searchQuery = $this->applyFullTextSearchSettings($searchQuery, $fullTextSearchAttributes);
            }
        }

        return $searchQuery;
    }

    /**
     * @param string $text
     * @param SearchableInterface $searchableType
     * @return Suggest|null
     */
    protected function getSuggestQuery($text, $searchableType)
    {
        $suggestionsFields = [];
        if ($searchableType instanceof IndexableInterface) {
            $indexAttributes = $searchableType->getIndexMapping();
            foreach ($indexAttributes as $attribute) {
                if (!empty($attribute->params['suggestions'])) {
                    $suggestionsFields[] = $attribute->name;
                }
            }
        }

        return $suggestionsFields ? new Suggest($suggestionsFields, $text) : null;
    }

    /**
     * @return \im\search\components\SearchManager
     */
    protected function getSearchManager()
    {
        if (!$this->_searchManager) {
            $this->_searchManager = Yii::$app->get('searchManager');
        }

        return $this->_searchManager;
    }
}