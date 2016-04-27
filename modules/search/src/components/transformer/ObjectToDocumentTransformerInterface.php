<?php

namespace im\search\components\transformer;

use im\search\components\index\Document;
use im\search\components\searchable\AttributeDescriptor;

/**
 * Maps index documents with objects.
 */
interface ObjectToDocumentTransformerInterface
{
    /**
     * Transforms an object into index document.
     *
     * @param object $object
     * @param AttributeDescriptor[] $attributes
     * @return Document
     **/
    public function transform($object, $attributes);
}