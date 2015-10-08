<?php

namespace im\search\models;

use im\search\components\query\facet\FacetInterface;
use im\search\components\query\facet\FacetValueInterface;
use im\search\components\query\SearchQueryInterface;
use yii\base\Model;

/**
 * Class FacetInterval
 * @package im\search\models
 */
class FacetInterval extends Model implements FacetValueInterface
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
     * @var SearchQueryInterface
     */
    private $_searchQuery;

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
        return $this->getKey();
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
}
