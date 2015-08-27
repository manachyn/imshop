<?php

namespace im\search\components\searchable;

use im\search\components\index\provider\IndexProviderInterface;
use im\search\components\transformer\DocumentToObjectTransformerInterface;

/**
 * Interface SearchableInterface
 * @package im\search\components
 */
interface SearchableInterface
{
    /**
     * Returns searchable attributes.
     *
     * @return AttributeDescriptor[]
     */
    public function getSearchableAttributes();

    /**
     * Returns indexable attributes.
     *
     * @return AttributeDescriptor[]
     */
    public function getIndexableAttributes();

    /**
     * Returns index provider.
     *
     * @return IndexProviderInterface
     */
    public function getIndexProvider();

    /**
     * Returns transformer.
     *
     * @return DocumentToObjectTransformerInterface
     */
    public function getTransformer();
}