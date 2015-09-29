<?php

namespace im\search\components\parser;

class Lexer
{

    public function tokenize($string, array $tokenMap)
    {
        $string = str_replace(array("\r", "\n", "\t", "\v", "\f"), ' ', $string);
        $tokens = array();
        $offset = 0;
        while (isset($string[$offset])) {
            foreach ($tokenMap as $regex => $token) {
                if (preg_match($regex, $string, $matches, null, $offset)) {
                    $tokens[] = array(
                        $token,      // token ID      (e.g. T_FIELD_SEPARATOR)
                        $matches[0], // token content (e.g. ,)
                    );
                    $offset += strlen($matches[0]);
                    continue 2; // continue the outer while loop
                }
            }

            throw new \LogicException(sprintf('Unexpected character "%s"', $string[$offset]));
        }

        return $tokens;
    }
}