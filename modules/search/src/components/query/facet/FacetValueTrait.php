<?php

namespace im\search\components\query\facet;

use im\search\components\query\SearchQueryHelper;
use im\search\components\query\SearchQueryInterface;
use im\search\models\Facet;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class FacetValueTrait
 *
 * @package im\search\components\query\facet
 */
trait FacetValueTrait
{
    /**
     * @var FacetInterface
     */
    private $_facet;

    /**
     * @var string
     */
    private $_key;

    /**
     * @var int
     */
    private $_resultsCount = 0;

    /**
     * @var array
     */
    private $_routeParams = [];

    /**
     * @inheritdoc
     */
    public function getFacet()
    {
        return $this->_facet;
    }

    /**
     * @inheritdoc
     */
    public function setFacet(FacetInterface $facet)
    {
        $this->_facet = $facet;
    }

    /**
     * @inheritdoc
     */
    public function getKey()
    {
        return $this->_key;
    }

    /**
     * @inheritdoc
     */
    public function setKey($key)
    {
        $this->_key = $key;
    }

    /**
     * @inheritdoc
     */
    public function getLabel()
    {
        return $this->getKey();
    }

    /**
     * @inheritdoc
     */
    public function getResultsCount()
    {
        return $this->_resultsCount;
    }

    /**
     * @inheritdoc
     */
    public function setResultsCount($count)
    {
        $this->_resultsCount = $count;
    }

    /**
     * @inheritdoc
     */
    public function isSelected(SearchQueryInterface $searchQuery = null)
    {
        return $searchQuery ? SearchQueryHelper::isIncludeQuery($searchQuery, $this->getValueSearchQuery(), $this->getValueOperator()) : false;
    }

    /**
     * @inheritdoc
     */
    public function getRouteParams()
    {
        return $this->getRouteParams();
    }

    /**
     * Sets route params.
     *
     * @param array $params
     */
    public function setRouteParams(array $params)
    {
        $this->_routeParams = $params;
    }

    /**
     * @inheritdoc
     */
    public function createUrl($route = null, SearchQueryInterface $searchQuery = null, $scheme = false)
    {
        $valueQuery = $this->getValueSearchQuery();
        if ($searchQuery) {
            $searchQuery = clone $searchQuery;
            if ($this->isSelected($searchQuery)) {
                $valueQuery = SearchQueryHelper::excludeQuery($searchQuery, $valueQuery, $this->getValueOperator());
            } else {
                if (!$this->getFacet()->isMultivalue()) {
                    $searchQuery = SearchQueryHelper::excludeQueryByFieldName($searchQuery, $valueQuery->getField());
                }
                if ($this->_routeParams) {
                    $valueQuery = $searchQuery;
                } else {
                    $valueQuery = SearchQueryHelper::includeQuery($searchQuery, $valueQuery, $this->getValueOperator());
                }
            }
        } elseif ($this->_routeParams) {
            $valueQuery = null;
        }
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = Yii::$app->get('searchManager');
        $searchComponent = $searchManager->getSearchComponent();

        if (!$route) {
            $currentParams = Yii::$app->getRequest()->getQueryParams();
            $currentParams[0] = '/' . Yii::$app->controller->getRoute();
            $route = ArrayHelper::merge($currentParams, $this->_routeParams);
        }
        if ($valueQuery) {
            $route['query'] = $searchComponent->queryConverter->toString($valueQuery);
        }

        return Url::toRoute($route, $scheme);
    }

    /**
     * @return \im\search\components\query\FieldQueryInterface
     */
    protected function getValueSearchQuery()
    {
        /** @var FacetValueInterface $value */
        $value = $this;

        return SearchQueryHelper::getQueryInstanceFromFacetValue($value);
    }

    /**
     * @return bool|null
     */
    protected function getValueOperator()
    {
        return $this->getFacet()->getOperator() === Facet::OPERATOR_AND ? true : null;
    }
}