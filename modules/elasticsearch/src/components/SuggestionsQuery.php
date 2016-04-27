<?php

namespace im\elasticsearch\components;

use im\search\components\query\QueryResultInterface;
use im\search\components\query\Suggest;
use im\search\components\query\SuggestionsQueryInterface;

/**
 * Class SuggestionQuery
 * @package im\elasticsearch\components
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
    public function createCommand($db = null)
    {
        $this->mapAggregations();
        if ($searchQuery = $this->getSearchQuery()) {
            $this->query = $this->mapQuery($searchQuery);
        }
        if ($suggestQuery = $this->getSuggestQuery()) {
            $this->suggest = $this->mapSuggestQuery($suggestQuery);
        }

        return parent::createCommand($db);
    }

    /**
     * @inheritdoc
     */
    public function result($db = null)
    {
        if (!$this->_result) {
            $response = $this->createCommand($db)->search();
            $this->_result = new SuggestionsQueryResult($this, $response);
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

    /**
     * @param Suggest $query
     * @return array
     */
    protected function mapSuggestQuery(Suggest $query)
    {
        $suggest = [];
        foreach ($query->getFields() as $field) {
            $suggest[$field . '_suggestion'] = ['text' => $query->getTerm(), 'completion' => ['field' => $field . '_suggest']];
        }

        return $suggest;
    }
}