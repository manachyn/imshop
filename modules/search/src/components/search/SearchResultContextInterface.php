<?php

namespace im\search\components\search;

use im\search\components\query\QueryResultInterface;

interface SearchResultContextInterface
{
    /**
     * @return QueryResultInterface
     */
    public function getResult();
}