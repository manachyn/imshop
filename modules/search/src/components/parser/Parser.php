<?php

namespace im\search\components\parser;

use Symfony\Component\ExpressionLanguage\TokenStream;
use ZendSearch\Lucene\Search\QueryParser;

class Parser
{
    private $_fsm;

    /**
     * Current query parser context
     *
     * @var ParserContext
     */
    private $_context;

    /**
     * Context stack
     *
     * @var array
     */
    private $_contextStack;

    public function parse(TokenStream $stream)
    {
        $fsm = $this->getFSM();
        foreach ($stream as $token) {
            //$fsm->process($token->);
        }
    }

    public function getFSM()
    {
        return new QueryParserFSM();
    }
}