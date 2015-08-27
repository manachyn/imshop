<?php

namespace im\search\components\query;

interface QueryResultInterface
{
    /**
     * @return int
     */
    public function getTotal();

    /**
     * @return array
     */
    public function getDocuments();

    /**
     * @return array
     */
    public function getObjects();

    /**
     * @return array
     */
    public function getFacets();

    /**
     * @return QueryInterface
     */
    public function getQuery();
}