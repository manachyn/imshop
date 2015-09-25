<?php

namespace im\search\components\searchable;

use im\search\components\index\provider\IndexProviderInterface;
use im\search\components\transformer\DocumentToObjectTransformerInterface;
use im\search\components\transformer\ObjectToDocumentTransformerInterface;

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
     * Returns index mapping.
     *
     * @return AttributeDescriptor[]
     */
    public function getIndexMapping();

    /**
     * Returns index provider.
     *
     * @return IndexProviderInterface
     */
    public function getIndexProvider();

    /**
     * Returns object to document transformer.
     *
     * @return ObjectToDocumentTransformerInterface
     */
    public function getObjectToDocumentTransformer();

    /**
     * Returns document to object transformer.
     *
     * @return DocumentToObjectTransformerInterface
     */
    public function getDocumentToObjectTransformer();
}