<?php

namespace im\catalog\components\search;


use im\catalog\models\CategoriesFacet;
use im\catalog\models\Category;
use im\search\components\index\IndexableInterface;
use im\search\components\query\FieldQueryInterface;
use im\search\components\query\QueryInterface;
use im\search\components\query\QueryResultEvent;
use im\search\components\query\SearchQueryHelper;
use im\search\components\query\Term;
use im\search\components\search\SearchComponent;
use im\search\components\searchable\SearchableInterface;
use yii\base\Component;
use yii\base\Model;

/**
 * Class CategorySearchComponent
 * @package im\catalog\components\search
 */
class CategorySearchComponent extends SearchComponent
{
    /**
     * @var array
     */
    private $_mapping = [];

    /**
     * @inheritdoc
     */
    public function getQuery($searchableType, $searchQuery = null, $facets = [], Model $model = null, $params = [])
    {
        /** @var Category $model */
        $searchComponent = $this->getSearchManager()->getSearchComponent();
        $searchableType = $this->normalizeSearchableType($searchableType);
        if ($searchQuery) {
            $searchQuery = $this->normalizeSearchableQuery($searchQuery, $searchableType);
        }

        // Add category query to search query
        $categoryQuery = $model instanceof Category ? $this->getCategoryFieldQuery($model, $searchableType) : null;
        if ($categoryQuery) {
            $searchQuery = $searchQuery ? SearchQueryHelper::includeQuery(clone $searchQuery, $categoryQuery) : $categoryQuery;
        }

        $query = $searchComponent->getQuery($searchableType, $searchQuery);

        $facetsFilter = null;
        // Exclude category query from search query, merge search query with category parents query and add it to categories facet as filter.
        // This will allow to calculate categories facet not for current category but also for children categories.
        if ($categoryQuery && array_filter($facets, function ($facet) { return $facet instanceof CategoriesFacet; })) {
            $facetsFilter = SearchQueryHelper::excludeQuery(clone $query->getSearchQuery(), $categoryQuery);
            // Search query by category parents
            $categoryParentsQuery = $this->getCategoryParentsQuery($model, $searchableType);
            if ($categoryParentsQuery) {
                $facetsFilter = SearchQueryHelper::includeQuery($facetsFilter, $categoryParentsQuery);
            }
        }
        foreach ($facets as $facet) {
            $facet->setContext($model);
            if ($facet instanceof CategoriesFacet) {
                if ($facetsFilter) {
                    $facet->setFilter($facetsFilter);
                }
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
}