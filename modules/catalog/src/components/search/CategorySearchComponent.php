<?php

namespace im\catalog\components\search;

use im\catalog\models\CategoriesFacet;
use im\catalog\models\Category;
use im\search\components\index\IndexableInterface;
use im\search\components\query\FieldQueryInterface;
use im\search\components\query\parser\QueryParserContextInterface;
use im\search\components\query\QueryInterface;
use im\search\components\query\QueryResultEvent;
use im\search\components\query\QueryResultInterface;
use im\search\components\query\SearchQueryHelper;
use im\search\components\query\SearchQueryInterface;
use im\search\components\query\Term;
use im\search\components\search\SearchDataProvider;
use im\search\components\searchable\SearchableInterface;
use im\search\models\FacetSet;
use Yii;
use yii\base\Component;

class CategorySearchComponent extends Component
{
    /**
     * @var \im\search\components\SearchManager
     */
    private $_searchManager;

    /**
     * @var array
     */
    private $_mapping = [];


    /**
     * Returns data provider for searchable type.
     *
     * @param SearchableInterface|string $searchableType
     * @param Category $category
     * @param SearchQueryInterface|string $searchQuery
     * @param array $params
     * @return SearchDataProvider
     */
    public function getSearchDataProvider($searchableType, Category $category, $searchQuery = null, $params = [])
    {
        $searchableType = $this->normalizeSearchableType($searchableType);
        $query = $this->getQuery($searchableType, $category, $searchQuery, $params);
        $dataProvider = new SearchDataProvider([
            'query' => $query
        ]);

        return $dataProvider;
    }

    /**
     * Return search query.
     *
     * @param string $searchableType
     * @param Category $category
     * @param SearchQueryInterface|string $searchQuery
     * @param array $params
     * @return \im\search\components\query\QueryInterface
     */
    public function getQuery($searchableType, Category $category, $searchQuery = null, $params = [])
    {
        $searchComponent = $this->getSearchManager()->getSearchComponent();
        $searchableType = $this->normalizeSearchableType($searchableType);

        // Parse search query
        if ($searchQuery && is_string($searchQuery)) {
            if ($searchableType instanceof QueryParserContextInterface) {
                $searchQuery = $searchComponent->parseQuery($searchQuery, $searchableType);
            } else {
                $searchQuery = $searchComponent->parseQuery($searchQuery);
            }

        }

        // Add category query to search query
        $categoryQuery = $this->getCategoryFieldQuery($category, $searchableType);
        if ($categoryQuery) {
            $searchQuery = $searchQuery ? SearchQueryHelper::includeQuery(clone $searchQuery, $categoryQuery) : $categoryQuery;
        }

        $query = $searchComponent->getQuery($searchableType, $searchQuery);

        $facets = $this->getFacets($category);
        $facetsFilter = null;
        // Exclude category query from search query, merge search query with category parents query and add it to categories facet as filter.
        // This will allow to calculate categories facet not for current category but also for children categories.
        if ($categoryQuery && array_filter($facets, function ($facet) { return $facet instanceof CategoriesFacet; })) {
            $facetsFilter = SearchQueryHelper::excludeQuery(clone $query->getSearchQuery(), $categoryQuery);
            // Search query by category parents
            $categoryParentsQuery = $this->getCategoryParentsQuery($category, $searchableType);
            if ($categoryParentsQuery) {
                $facetsFilter = SearchQueryHelper::includeQuery($facetsFilter, $categoryParentsQuery);
            }
        }
        foreach ($facets as $facet) {
            $facet->setContext($category);
            if ($facet instanceof CategoriesFacet && $facetsFilter) {
                $facet->setFilter($facetsFilter);
                if (isset($params['categoriesFacetValueRouteParams'])) {
                    $facet->setValueRouteParams($params['categoriesFacetValueRouteParams']);
                }
            }
        }
        $query->setFacets($facets);

        // Exclude category query from search query as we will add category to facet route parameters
        if ($categoryQuery) {
            /** @var QueryInterface|Component $query */
            $query->on(QueryInterface::EVENT_AFTER_RESULT, function (QueryResultEvent $event) use ($categoryQuery) {
                $searchQuery = $event->queryResult->getQuery()->getSearchQuery();
                if ($searchQuery) {
                    $event->queryResult->getQuery()->setSearchQuery(SearchQueryHelper::excludeQuery($searchQuery, $categoryQuery));
                }
            });
        }

        return $query;
    }

    /**
     * Returns category facets.
     *
     * @param Category $category
     * @return \im\search\components\query\facet\FacetInterface[]
     */
    public function getFacets(Category $category)
    {
        $facetSet = FacetSet::findOne(1);
        $facets = $facetSet->facets;
        return $facets;
    }

    public function setFacetsQueryResult(QueryResultInterface $queryResult)
    {

    }

    /**
     * @param SearchableInterface|string $searchableType
     * @return SearchableInterface
     */
    protected function normalizeSearchableType($searchableType)
    {
        if (is_string($searchableType)) {
            /** @var \im\search\components\SearchManager $searchManager */
            $searchManager = Yii::$app->get('searchManager');
            $searchableType = $searchManager->getSearchableType($searchableType);
        }

        return $searchableType;
    }

    /**
     * @param Category $category
     * @param SearchableInterface $searchableType
     * @return FieldQueryInterface
     */
    protected function getCategoryFieldQuery(Category $category, SearchableInterface $searchableType)
    {
        $categoryQuery = null;
        $mapping = $this->getSearchableTypeMapping($searchableType);
        if ($mapping) {
            foreach ($mapping as $name => $attribute) {
                $nameParts = explode('.', $name);
                if (count($nameParts) == 2 && $nameParts[0] == 'categories') {
                    $categoryQuery = new Term($attribute->name, $category->{$nameParts[1]});
                }
            }
        } else {
            $categoryQuery = new Term('categoriesRelation.id', $category->id);
        }

        return $categoryQuery;
    }

    /**
     * @param Category $category
     * @param SearchableInterface $searchableType
     * @return FieldQueryInterface
     */
    protected function getCategoryParentsQuery(Category $category, SearchableInterface $searchableType)
    {
        $categoryParentsQuery = null;
        $mapping = $this->getSearchableTypeMapping($searchableType);
        if ($mapping) {
            foreach ($mapping as $name => $attribute) {
                if ($name == 'all_categories') {
                    $categoryParentsQuery = new Term($attribute->name, $category->id);
                    break;
                }
            }
        }

        return $categoryParentsQuery;
    }

    /**
     * @param SearchableInterface $searchableType
     * @return \im\search\components\searchable\AttributeDescriptor[]
     */
    protected function getSearchableTypeMapping(SearchableInterface $searchableType)
    {
        $mapping = [];
        $type = $searchableType->getType();
        if ($searchableType instanceof IndexableInterface) {
            if (isset($this->_mapping[$type])) {
                $mapping = $this->_mapping[$type];
            } else {
                $this->_mapping[$type] = $mapping = $searchableType->getIndexMapping();
            }
        }

        return $mapping;
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