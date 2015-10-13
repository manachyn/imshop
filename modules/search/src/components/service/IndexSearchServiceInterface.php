<?php

namespace im\search\components\service;

use im\search\components\index\IndexerInterface;

/**
 * Interface of indexed search service.
 *
 * @package im\search\components\service
 */
interface IndexSearchServiceInterface extends SearchServiceInterface
{
    /**
     * Returns indexer.
     *
     * @return IndexerInterface
     */
    public function getIndexer();
}