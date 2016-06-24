<?php

namespace im\search\components\service\db;

use im\search\components\query\QueryResultInterface;
use im\search\components\query\Suggest;
use im\search\components\query\SuggestionsQueryInterface;

/**
 * Class SuggestionsQuery
 * @package im\search\components\service\db
 */
class SuggestionsQuery extends Query implements SuggestionsQueryInterface
{
    /**
     * @var QueryResultInterface
     */
    private $_result;

    /**
     * @var Suggest
     */
    private $_suggestQuery;

    /**
     * @inheritdoc
     */
    public function result($db = null)
    {
        if (!$this->_result) {
            $this->_result = new QueryResult($this, $this->all());
        }

        return $this->_result;
    }

    /**
     * @inheritdoc
     */
    public function setSuggestQuery(Suggest $suggestQuery)
    {
        $this->_suggestQuery = $suggestQuery;
    }

    /**
     * @inheritdoc
     */
    public function getSuggestQuery()
    {
        return $this->_suggestQuery;
    }
}