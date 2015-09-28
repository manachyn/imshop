<?php

namespace im\search\models;

use im\search\components\query\FacetValueInterface;
use yii\base\Model;

class FacetInterval extends Model implements FacetValueInterface
{
    /**
     * @var string
     */
    private $_key;

    /**
     * @var int
     */
    private $_resultsCount = 0;

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
}
