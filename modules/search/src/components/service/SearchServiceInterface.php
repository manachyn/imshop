<?php

namespace im\search\components\service;

use im\search\components\finder\FinderInterface;
use im\search\components\index\IndexerInterface;

/**
 * Interface SearchServiceInterface
 * @package im\search\components
 */
interface SearchServiceInterface
{
    /**
     * Returns service name.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns indexer.
     *
     * @return IndexerInterface
     */
    public function getIndexer();

    /**
     * Returns finder.
     *
     * @return FinderInterface
     */
    public function getFinder();
}