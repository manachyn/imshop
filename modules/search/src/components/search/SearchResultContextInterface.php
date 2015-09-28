<?php

namespace im\search\components;

use im\search\components\query\QueryResultInterface;

interface SearchResultContextInterface
{
    /**
     * @return QueryResultInterface
     */
    public function getResult();
}