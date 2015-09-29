<?php

namespace im\search\components\parser;

class QueryLexer
{
    public function tokenize($string)
    {

        $string = 'title=one-or-to';
        $string2 = 'title:one or to';
        $cursor = 0;
        $tokens = array();
        $brackets = array();
        $end = strlen($string);

        $patterns = [
            '/[0-9]+(?:\.[0-9]+)?/A' => QueryToken::TYPE_NUMBER,
            '/"([^"\\\\]*(?:\\\\.[^"\\\\]*)*)"|\'([^\'\\\\]*(?:\\\\.[^\'\\\\]*)*)\'/As' => QueryToken::TYPE_PHRASE,
            '/not in(?=[\s(])|\!\=\=|not(?=[\s(])|and(?=[\s(])|\=\=\=|\=|\-or\-|\>\=|or(?=[\s(])|\<\=|\*\*|\.\.|in(?=[\s(])|&&|\|\||matches|\=\=|\!\=|\*|~|%|\/|\>|\||\!|\^|&|\+|\<|\-/A' => QueryToken::TYPE_OPERATOR,
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

        $b = 1;
    }
} 