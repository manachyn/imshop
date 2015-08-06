<?php

namespace im\search\components;

/**
 * Interface SearchProviderInterface
 * @package im\search\components
 */
interface SearchProviderInterface
{
    /**
     * Returns searchable attributes of entity.
     *
     * @return array
     */
    public function getSearchableAttributes();

    /**
     * Returns search index attributes of entity.
     *
     * @return array
     */
    public function getIndexAttributes();
}