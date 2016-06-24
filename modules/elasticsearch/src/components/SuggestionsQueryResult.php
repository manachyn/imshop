<?php

namespace im\elasticsearch\components;

use im\search\components\query\SuggestionOption;
use im\search\components\query\SuggestionsQueryInterface;

/**
 * Class SuggestionsQueryResult
 * @package im\elasticsearch\components
 */
class SuggestionsQueryResult extends \im\search\components\query\SuggestionsQueryResult
{
    /**
     * @var array
     */
    private $_response;

    /**
     * @var SuggestionOption[]
     */
    private $_suggestions = [];

    /**
     * SuggestionsQueryResult constructor.
     * @param SuggestionsQueryInterface $query
     * @param array $response
     */
    function __construct(SuggestionsQueryInterface $query, array $response)
    {
        parent::__construct($query);
        $this->init($response);
    }

    /**
     * @inheritdoc
     */
    public function getSuggestions()
    {
        return $this->_suggestions;
    }

    /**
     * Init result.
     *
     * @param array $response
     */
    protected function init(array $response)
    {
        $this->_response = $response;
        if (isset($response['suggest'])) {
            $this->_suggestions = $this->parseSuggestions($response['suggest']);
        }
    }

    /**
     * Creates result suggestions from response.
     *
     * @param $responseSuggest
     * @return SuggestionOption[]
     */
    private function parseSuggestions($responseSuggest)
    {
        $options = [];
        foreach ($responseSuggest as $field => $suggestions) {
            $field = substr($field, 0, strlen($field) - 11);
            foreach ($suggestions as $suggestion) {
                foreach ($suggestion['options'] as $option) {
                    $options[] = new SuggestionOption($field, $option['text'], $option['score']);
                }
            }
        }

        return $options;
    }
}