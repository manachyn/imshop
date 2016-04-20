<?php

namespace im\search\components\index;

use im\search\components\searchable\AttributeDescriptor;

/**
 * Interface IndexerInterface.
 *
 * @package im\search\components\index
 */
interface IndexerInterface
{
    /**
     * Clears index by type and indexes object.
     *
     * @param IndexInterface $index
     * @param string $type
     * @param array $options
     * @param \Closure $progress
     * @return IndexActionResult
     */
    public function reindex(IndexInterface $index, $type, array $options, \Closure $progress = null);

    /**
     * Inserts objects to the index.
     *
     * @param IndexInterface $index
     * @param string $type
     * @param array $objects
     * @return BulkResponse
     */
    public function insertObjects(IndexInterface $index, $type, $objects);

    /**
     * Inserts object to the index.
     *
     * @param IndexInterface $index
     * @param string $type
     * @param object $object
     * @return Response
     */
    public function insertObject(IndexInterface $index, $type, $object);

    /**
     * Deletes index.
     *
     * @param IndexInterface $index
     * @return Response
     */
    public function deleteIndex(IndexInterface $index);

    /**
     * Deletes index type.
     *
     * @param IndexInterface $index
     * @param string $type
     * @return Response
     */
    public function deleteIndexType(IndexInterface $index, $type);

    /**
     * Inserts document to the index.
     *
     * @param Document $document
     * @param array $options
     * @return Response
     */
    public function insertDocument(Document $document, array $options = []);

    /**
     * Insert documents set to the index.
     *
     * @param Document[] $documents
     * @param array $options
     * @return BulkResponse
     */
    public function insertDocuments($documents, array $options = []);

    /**
     * Deletes documents by ids.
     *
     * @param IndexInterface $index
     * @param string $type
     * @param array $ids
     * @param array $options
     * @return Response
     */
    public function deleteById(IndexInterface $index, $type, $ids, array $options = []);

    /**
     * Deletes index, index type or document by id.
     *
     * @param IndexInterface $index
     * @param string $type
     * @param mixed $id
     * @param array $options
     * @return Response
     */
    public function delete(IndexInterface $index, $type = null, $id = null, array $options = []);

    /**
     * Gets action result from response.
     *
     * @param Response $response
     * @return IndexActionResult
     */
    public function getIndexActionResult(Response $response);


    /**
     * Set index mapping for type.
     *
     * @param IndexInterface $index
     * @param string $type
     * @param AttributeDescriptor[] $attributes
     */
    public function setMapping(IndexInterface $index, $type, array $attributes);


    /**
     * Whether index type exists.
     *
     * @param IndexInterface $index
     * @param string $type
     * @return bool
     */
    public function typeExists(IndexInterface $index, $type);
}