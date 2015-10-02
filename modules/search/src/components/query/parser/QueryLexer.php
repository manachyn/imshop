<?php

namespace im\search\components\query\parser;

/**
 * Class QueryLexer
 * @package im\search\components\query\parser
 */
class QueryLexer implements QueryLexerInterface
{
    /**
     * @inheritdoc
     */
    public function tokenize($string)
    {
        $cursor = 0;
        $tokens = array();
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
            throw new SyntaxException(sprintf("Unexpected character '%s' around position %d.", $string[$cursor], $cursor));
        }

        return $tokens;
    }
} 