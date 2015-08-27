<?php

namespace im\search\components\index;

use im\search\components\service\SearchServiceInterface;

interface IndexInterface
{
    /**
     * Returns index name.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns index type.
     *
     * @return string
     */
    public function getType();

    /**
     * Returns search service.
     *
     * @return SearchServiceInterface
     */
    public function getSearchService();
}