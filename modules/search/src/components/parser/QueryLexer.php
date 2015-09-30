<?php

namespace im\search\components\parser;

class QueryLexer
{
    public function tokenize($string)
    {

        $string = 'title=one OR two&date=[2015-15-10 to 30]&test=NOT ggg AND (dfsdf OR dfsdf)&field>=20';
        $string2 = 'title:one or to';
        $cursor = 0;
        $tokens = array();
        $brackets = array();
        $end = strlen($string);

        $patterns = [
            '/([0-9]){4}-([0-9]){2}-([0-9]){2}/A' => QueryToken::TYPE_DATE,
            '/[0-9]+(?:\.[0-9]+)?/A' => QueryToken::TYPE_NUMBER,
            '/"([^"\\\\]*(?:\\\\.[^"\\\\]*)*)"|\'([^\'\\\\]*(?:\\\\.[^\'\\\\]*)*)\'/As' => QueryToken::TYPE_PHRASE,
            '/not(?=[\s(])|and(?=[\s(])|or(?=[\s(])|\=|\>\=|\<\=|\>|\<|&/A' => QueryToken::TYPE_OPERATOR,
            '/\[|\]|\(|\)|\-/A' => QueryToken::TYPE_SYNTAX,
            '/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/A' => QueryToken::TYPE_WORD
        ];

        while ($cursor < $end) {
            if (' ' == $string[$cursor]) {
                ++$cursor;
                continue;
            }
            foreach ($patterns as $pattern => $type) {
                if (preg_match($pattern, $string, $match, null, $cursor)) {
                    $tokens[] = new QueryToken($type, $match[0], $cursor);
                    $cursor += strlen($match[0]);
                    continue 2;
                }
            }
            throw new \LogicException(sprintf('Unexpected character "%s" around position %d.', $string[$cursor], $cursor));
        }

        $lexer2 = new \ZendSearch\Lucene\Search\QueryLexer();
        $tokens2 = $lexer2->tokenize($string2, '');

        return $tokens;
    }
} 