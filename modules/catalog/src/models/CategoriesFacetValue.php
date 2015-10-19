<?php

namespace im\catalog\models;

use im\search\components\query\facet\EntityFacetValueInterface;
use im\search\components\query\facet\FacetInterface;
use im\search\components\query\SearchQueryInterface;
use yii\base\Object;

class CategoriesFacetValue extends Object implements EntityFacetValueInterface
{
    /**
     * @var FacetInterface
     */
    protected $_facet;

    /**
     * @var string
     */
    protected $_key;

    /**
     * @var int
     */
    protected $_resultsCount = 0;

    /**
     * @var SearchQueryInterface
     */
    protected $_searchQuery;

    /**
     * @var bool
     */
    protected $_isSelected = false;

    /**
     * @var Category
     */
    protected $_category;

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
    public function getLabel()
    {
        return $this->_category->name;
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
    }

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
    public function setSelected($selected)
    {
        $this->_isSelected = $selected;
    }

    /**
     * @inheritdoc
     */
    public function isSelected()
    {
        return $this->_isSelected;
    }

    /**
     * @inheritdoc
     */
    public function getEntity()
    {
        return $this->_category;
    }

    /**
     * @inheritdoc
     */
    public function setEntity($entity)
    {
        $this->_category = $entity;
    }
}