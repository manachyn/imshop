<?php

namespace im\search\components\query\parser;
use im\search\components\query\BooleanQueryInterface;
use im\search\components\query\FieldQueryInterface;
use im\search\components\query\RangeInterface;
use im\search\components\query\SearchQueryInterface;
use im\search\components\query\Term;

/**
 * Query converter used to modify search query and convert it back to string.
 *
 * @package im\search\components\query\parser
 */
class QueryConverter implements QueryConverterInterface
{
    const SYNTAX_EQUAL_OPERATOR = 1;
    const SYNTAX_TOP_LEVEL_AND_OPERATOR = 2;
    const SYNTAX_TOP_LEVEL_OR_OPERATOR = 3;
    const SYNTAX_TOP_LEVEL_NOT_OPERATOR = 4;
    const SYNTAX_AND_OPERATOR = 5;
    const SYNTAX_OR_OPERATOR = 6;
    const SYNTAX_NOT_OPERATOR = 7;
    const SYNTAX_INCLUDED_RANGE_START = 8;
    const SYNTAX_INCLUDED_RANGE_END = 9;
    const SYNTAX_EXCLUDED_RANGE_START = 10;
    const SYNTAX_EXCLUDED_RANGE_END = 11;
    const SYNTAX_RANGE_TO_TERM = 12;

    public $syntax = [
        self::SYNTAX_EQUAL_OPERATOR => '=',
        self::SYNTAX_TOP_LEVEL_AND_OPERATOR => '&',
        self::SYNTAX_TOP_LEVEL_OR_OPERATOR => ' or ',
        self::SYNTAX_TOP_LEVEL_NOT_OPERATOR => ' not ',
        self::SYNTAX_AND_OPERATOR => ' and ',
        self::SYNTAX_OR_OPERATOR => ' or ',
        self::SYNTAX_NOT_OPERATOR => ' not ',
        self::SYNTAX_INCLUDED_RANGE_START => '[',
        self::SYNTAX_INCLUDED_RANGE_END => ']',
        self::SYNTAX_EXCLUDED_RANGE_START => '(',
        self::SYNTAX_EXCLUDED_RANGE_END => ')',
        self::SYNTAX_RANGE_TO_TERM => ' to '
    ];

    /**
     * @inheritdoc
     */
    public function toString(SearchQueryInterface $query)
    {
        return $this->queryToString($query);
    }

    protected function queryToString(SearchQueryInterface $query, $asValue = false, $topLevelQuery = true)
    {
        $queryString = '';
        if ($query instanceof BooleanQueryInterface) {
            $subQueries = $query->getSubQueries();
            $groupedQueries = [];
            $operator = $this->getOperator(reset($query->getSigns()), $topLevelQuery);
            foreach ($subQueries as $subQuery) {
                if ($field = $this->getQueryField($subQuery)) {
                    $groupedQueries[$field][] = $subQuery;
                } else {
                    $groupedQueries[] = $subQuery;
                }
            }
            $queryStringParts = [];
            foreach ($groupedQueries as $field => $queries) {
                if (!is_array($queries)) {
                    $queries = [$queries];
                }
                $queryParts = [];
                foreach ($queries as $query) {
                    $queryParts[] = $this->queryToString($query, is_string($field), false);
                }
                $queryStringParts[] = (!$asValue && is_string($field) ? $field . $this->syntax[self::SYNTAX_EQUAL_OPERATOR] : '') . implode($operator, $queryParts);
            }
            $queryString = implode($operator, $queryStringParts);
        } elseif ($query instanceof FieldQueryInterface) {
            if ($query instanceof RangeInterface) {
                $queryString = $query->isIncludeLowerBound() ? $this->syntax[self::SYNTAX_INCLUDED_RANGE_START] : $this->syntax[self::SYNTAX_EXCLUDED_RANGE_START];
                $queryString .= $query->getLowerBound() !== null ? $query->getLowerBound() : '';
                $queryString .= $this->syntax[self::SYNTAX_RANGE_TO_TERM];
                $queryString .= $query->getUpperBound() !== null ? $query->getUpperBound() : '';
                $queryString .= $query->isIncludeUpperBound() ? $this->syntax[self::SYNTAX_INCLUDED_RANGE_END] : $this->syntax[self::SYNTAX_EXCLUDED_RANGE_END];
            } elseif ($query instanceof Term) {
                $queryString = $query->getTerm();
            }
            if ($queryString && !$asValue) {
                $queryString = $query->getField() . $this->syntax[self::SYNTAX_EQUAL_OPERATOR] . $queryString;
            }
        }

        return $queryString;
    }

    private function getOperator($sign, $topLevel = true)
    {
        if ($topLevel) {
            $operator = $sign === true ? $this->syntax[self::SYNTAX_TOP_LEVEL_AND_OPERATOR]
                : ($sign === false ? $this->syntax[self::SYNTAX_TOP_LEVEL_NOT_OPERATOR]
                    : $this->syntax[self::SYNTAX_TOP_LEVEL_OR_OPERATOR]);
        } else {
            $operator = $sign === true ? $this->syntax[self::SYNTAX_AND_OPERATOR]
                : ($sign === false ? $this->syntax[self::SYNTAX_NOT_OPERATOR] : $this->syntax[self::SYNTAX_OR_OPERATOR]);
        }

        return $operator;
    }

    private function getQueryField(SearchQueryInterface $query)
    {
        $field = null;
        if ($query instanceof BooleanQueryInterface) {
            foreach ($query->getSubQueries() as $subQuery) {
                $subQueryField = $this->getQueryField($subQuery);
                if ($field && (!$subQueryField || $field !== $subQueryField)) {
                    return false;
                }
                if ($subQueryField) {
                    $field = $subQueryField;
                }
            }
        } elseif ($query instanceof FieldQueryInterface) {
            $field = $query->getField();
        }

        return $field;
    }
}