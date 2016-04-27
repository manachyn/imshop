<?php

namespace im\search\components\query\parser;

/**
 * Interface QueryLexerInterface
 * @package im\search\components\query\parser
 */
interface QueryLexerInterface
{
    /**
     * Tokenize query string into lexemes.
     *
     * @param string $queryString
     * @return QueryToken[]
     * @throws SyntaxException
     */
    public function tokenize($queryString);
}