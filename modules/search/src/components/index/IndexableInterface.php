<?php

namespace im\search\components\index;

use im\search\components\index\provider\IndexProviderInterface;
use im\search\components\searchable\AttributeDescriptor;
use im\search\components\transformer\DocumentToObjectTransformerInterface;
use im\search\components\transformer\ObjectToDocumentTransformerInterface;

/**
 * Interface of indexable type.
 *
 * @package im\search\components\index
 */
interface IndexableInterface
{
    /**
     * Returns index.
     *
     * @return IndexInterface
     */
    public function getIndex();

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