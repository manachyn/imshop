<?php

namespace im\search\components\index;

use im\search\components\index\provider\IndexProviderInterface;
use im\search\components\searchable\AttributeDescriptor;
use im\search\components\searchable\SearchableInterface;
use im\search\components\transformer\ObjectToDocumentTransformerInterface;
use Yii;

/**
 * Indexer base class.
 * Implements base methods for adding objects to the index and removing them.
 *
 * @package im\search\components\index
 */
abstract class BaseIndexer implements IndexerInterface
{
    /**
     * @inheritdoc
     */
    public function reindex(IndexInterface $index, $type, array $options, \Closure $progress = null)
    {
        $offset = isset($options['offset']) ? $options['offset'] : 0;
        $limit = isset($options['batchSize']) ? $options['batchSize'] : null;
        $ignoreErrors = isset($options['ignoreErrors']) ? $options['ignoreErrors'] : false;

        $this->deleteIndexType($index, $type);

        $provider = $this->getIndexProvider($type);
        $total = $provider->countObjects($offset);
        $result = new IndexActionResult('index');
        $result->total = $total;
        do {
            try {
                $objects = $provider->getObjects($limit, $offset);
                if ($objects) {
                    $response = $this->insertObjects($index, $type, $objects);
                    $result->add($this->getIndexActionResult($response));
                }
            } catch (\Exception $e) {
                if (!$ignoreErrors) {
                    throw $e;
                }

                if ($progress !== null) {
                    $progress($offset, $total, sprintf('<error>%s</error>', $e->getMessage()));
                }
            }
            $offset += $limit;

            usleep($options['sleep']);

            if ($progress !== null) {
                $progress($offset, $total);
            }

        } while ($limit && $offset < $total);

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function insertObjects(IndexInterface $index, $type, $objects)
    {
        $transformer = $this->getTransformer($type);
        $mapping = $this->getIndexMapping($type);
        $documents = [];
        foreach ($objects as $object) {
            $document = $transformer->transform($object, $mapping);
            $document->setIndex($index->getName());
            $document->setType($type);
            $documents[] = $document;
        }

        return $this->insertDocuments($documents);
    }

    /**
     * @inheritdoc
     */
    public function insertObject(IndexInterface $index, $type, $object)
    {
        $transformer = $this->getTransformer($type);
        $mapping = $this->getIndexMapping($type);
        $document = $transformer->transform($object, $mapping);
        $document->setIndex($index->getName());
        $document->setType($type);
        return $this->insertDocument($document);
    }

    /**
     * @inheritdoc
     */
    public function deleteIndex(IndexInterface $index)
    {
        return $this->delete($index);
    }

    /**
     * @inheritdoc
     */
    public function deleteIndexType(IndexInterface $index, $type)
    {
        return $this->delete($index, $type);
    }

    /**
     * Returns searchable type by index type.
     *
     * @param string $type
     * @return SearchableInterface
     */
    public function getSearchableType($type)
    {
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = Yii::$app->get('searchManager');

        return $searchManager->getSearchableType($type);
    }

    /**
     * Returns object to index document transformer.
     *
     * @param string $type
     * @return ObjectToDocumentTransformerInterface
     */
    public function getTransformer($type)
    {
        return $this->getSearchableType($type)->getObjectToDocumentTransformer();
    }

    /**
     * Returns index provider by index type.
     *
     * @param string $type
     * @return IndexProviderInterface
     */
    public function getIndexProvider($type)
    {
        return $this->getSearchableType($type)->getIndexProvider();
    }

    /**
     * Returns index mapping by index type.
     *
     * @param string $type
     * @return AttributeDescriptor[]
     */
    public function getIndexMapping($type)
    {
        return $this->getSearchableType($type)->getIndexMapping();
    }
}