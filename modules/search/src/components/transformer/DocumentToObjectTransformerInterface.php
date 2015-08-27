<?php

namespace im\search\components\transformer;
use im\search\components\index\Document;

/**
 * Maps result documents with model objects.
 */
interface DocumentToObjectTransformerInterface
{
    /**
     * Transforms result documents into an array of objects.
     *
     * @param Document[] $documents
     * @return array
     **/
    public function transform($documents);

    /**
     * Returns the object class used by the transformer.
     *
     * @return string
     */
    public function getObjectClass();

    /**
     * Sets the object class used by the transformer.
     *
     * @param string $objectClass
     */
    public function setObjectClass($objectClass);

    /**
     * Returns the identifier field from the options.
     *
     * @return string the identifier field
     */
    public function getIdentifierField();
}
