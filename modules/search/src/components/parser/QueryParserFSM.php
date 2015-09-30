<?php

namespace im\search\components\parser;

use im\fsm\FSM;
use im\fsm\FSMAction;

class QueryParserFSM extends FSM
{
    const STATE_QUERY_ELEMENT = 0;
    const STATE_INCLUDED_RANGE_START         = 1;
    const STATE_INCLUDED_RANGE_FIRST_TERM    = 2;
    const STATE_INCLUDED_RANGE_TO_TERM    = 3;
    const STATE_INCLUDED_RANGE_LAST_TERM    = 4;
    const STATE_INCLUDED_RANGE_END    = 5;
    const STATE_EXCLUDED_RANGE_START         = 6;
    const STATE_EXCLUDED_RANGE_FIRST_TERM    = 7;
    const STATE_EXCLUDED_RANGE_TO_TERM    = 8;
    const STATE_EXCLUDED_RANGE_LAST_TERM    = 9;
    const STATE_EXCLUDED_RANGE_END    = 10;
    const STATE_OPERATOR    = 11;

    function __construct()
    {
        $this->addInputSymbols(QueryToken::getTypes());

        $this->addStates([
            self::STATE_QUERY_ELEMENT,
            self::STATE_INCLUDED_RANGE_START,
            self::STATE_INCLUDED_RANGE_FIRST_TERM,
            self::STATE_INCLUDED_RANGE_TO_TERM,
            self::STATE_INCLUDED_RANGE_LAST_TERM,
            self::STATE_INCLUDED_RANGE_END,
            self::STATE_EXCLUDED_RANGE_START,
            self::STATE_EXCLUDED_RANGE_FIRST_TERM,
            self::STATE_EXCLUDED_RANGE_TO_TERM,
            self::STATE_EXCLUDED_RANGE_LAST_TERM,
            self::STATE_EXCLUDED_RANGE_END,
        ]);

        $this->addTransitions([
            [self::STATE_QUERY_ELEMENT, QueryToken::TYPE_WORD, self::STATE_QUERY_ELEMENT],
            [self::STATE_QUERY_ELEMENT, QueryToken::TYPE_PHRASE, self::STATE_QUERY_ELEMENT],
            [self::STATE_QUERY_ELEMENT, QueryToken::TYPE_NUMBER, self::STATE_QUERY_ELEMENT],
            [self::STATE_QUERY_ELEMENT, QueryToken::TYPE_DATE, self::STATE_QUERY_ELEMENT],
            //[self::STATE_QUERY_ELEMENT, QueryToken::TYPE_FIELD, self::STATE_QUERY_ELEMENT],
            [self::STATE_QUERY_ELEMENT, QueryToken::TYPE_OPERATOR, self::STATE_QUERY_ELEMENT],
            [self::STATE_QUERY_ELEMENT, QueryToken::TYPE_AND_OPERATOR, self::STATE_QUERY_ELEMENT],
            [self::STATE_QUERY_ELEMENT, QueryToken::TYPE_OR_OPERATOR, self::STATE_QUERY_ELEMENT],
            [self::STATE_QUERY_ELEMENT, QueryToken::TYPE_NOT_OPERATOR, self::STATE_QUERY_ELEMENT],
            [self::STATE_QUERY_ELEMENT, QueryToken::TYPE_LEFT_SQUARE_BRACKET, self::STATE_INCLUDED_RANGE_START],
            //[self::STATE_QUERY_ELEMENT, QueryToken::TYPE_LEFT_PARENTHESIS, self::STATE_EXCLUDED_RANGE_START],
            [self::STATE_QUERY_ELEMENT, QueryToken::TYPE_LEFT_PARENTHESIS, self::STATE_QUERY_ELEMENT],
            [self::STATE_QUERY_ELEMENT, QueryToken::TYPE_RIGHT_PARENTHESIS, self::STATE_QUERY_ELEMENT],
        ]);

        $this->addTransitions([
            [self::STATE_INCLUDED_RANGE_START, QueryToken::TYPE_WORD, self::STATE_INCLUDED_RANGE_FIRST_TERM],
            [self::STATE_INCLUDED_RANGE_START, QueryToken::TYPE_DATE, self::STATE_INCLUDED_RANGE_FIRST_TERM],
            [self::STATE_INCLUDED_RANGE_START, QueryToken::TYPE_NUMBER, self::STATE_INCLUDED_RANGE_FIRST_TERM],
            [self::STATE_INCLUDED_RANGE_FIRST_TERM, QueryToken::TYPE_TO_LEXEME, self::STATE_INCLUDED_RANGE_TO_TERM],
            [self::STATE_INCLUDED_RANGE_TO_TERM, QueryToken::TYPE_WORD, self::STATE_INCLUDED_RANGE_LAST_TERM],
            [self::STATE_INCLUDED_RANGE_TO_TERM, QueryToken::TYPE_DATE, self::STATE_INCLUDED_RANGE_LAST_TERM],
            [self::STATE_INCLUDED_RANGE_TO_TERM, QueryToken::TYPE_NUMBER, self::STATE_INCLUDED_RANGE_LAST_TERM],
            [self::STATE_INCLUDED_RANGE_LAST_TERM, QueryToken::TYPE_RIGHT_SQUARE_BRACKET, self::STATE_QUERY_ELEMENT],
        ]);

        $this->addTransitions([
            [self::STATE_EXCLUDED_RANGE_START, QueryToken::TYPE_WORD, self::STATE_EXCLUDED_RANGE_FIRST_TERM],
            [self::STATE_EXCLUDED_RANGE_START, QueryToken::TYPE_DATE, self::STATE_EXCLUDED_RANGE_FIRST_TERM],
            [self::STATE_EXCLUDED_RANGE_START, QueryToken::TYPE_NUMBER, self::STATE_EXCLUDED_RANGE_FIRST_TERM],
            [self::STATE_EXCLUDED_RANGE_FIRST_TERM, QueryToken::TYPE_TO_LEXEME, self::STATE_EXCLUDED_RANGE_TO_TERM],
            [self::STATE_EXCLUDED_RANGE_TO_TERM, QueryToken::TYPE_WORD, self::STATE_EXCLUDED_RANGE_LAST_TERM],
            [self::STATE_EXCLUDED_RANGE_TO_TERM, QueryToken::TYPE_DATE, self::STATE_EXCLUDED_RANGE_LAST_TERM],
            [self::STATE_EXCLUDED_RANGE_TO_TERM, QueryToken::TYPE_NUMBER, self::STATE_EXCLUDED_RANGE_LAST_TERM],
            [self::STATE_EXCLUDED_RANGE_LAST_TERM, QueryToken::TYPE_RIGHT_PARENTHESIS, self::STATE_QUERY_ELEMENT],
        ]);
    }
}