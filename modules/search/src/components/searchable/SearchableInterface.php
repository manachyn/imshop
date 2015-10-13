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
     * Returns search service.
     *
     * @return SearchServiceInterface
     */
    public function getSearchService();

    /**
     * Returns searchable attributes.
     *
     * @return AttributeDescriptor[]
     */
    public function getSearchableAttributes();
}