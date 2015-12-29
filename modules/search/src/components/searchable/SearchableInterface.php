<?php

namespace im\search\components\searchable;

use im\search\components\service\SearchServiceInterface;

/**
 * Interface of searchable type.
 *
 * @package im\search\components
 */
interface SearchableInterface
{
    /**
     * Returns type.
     *
     * @return string
     */
    public function getType();

    /**
     * Returns class.
     *
     * @return string
     */
    public function getClass();

    /**
     * Returns search service.
     *
     * @return SearchServiceInterface
     */
    public function getSearchService();

    /**
     * Returns searchable attributes.
     *
     * @param bool $recursive
     * @return AttributeDescriptor[]
     */
    public function getSearchableAttributes($recursive = false);

    /**
     * Returns full text search attributes.
     *
     * @return AttributeDescriptor[]
     */
    public function getFullTextSearchAttributes();

    /**
     * Returns the name of the view for rendering search results.
     *
     * @return string
     */
    public function getSearchResultsView();
}