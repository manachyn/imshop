<?php

namespace im\search\components\index;

use im\search\components\index\provider\IndexProviderInterface;
use im\search\components\searchable\AttributeDescriptor;
use im\search\components\searchable\SearchableInterface;
use im\search\components\transformer\ObjectToDocumentTransformerInterface;
use Yii;

abstract class BaseIndexer implements IndexerInterface
{
    private $_transformer = 'im\search\components\transformer\ObjectToDocumentTransformer';

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
        $attributes = $this->getIndexableAttributes($type);
        $documents = [];
        foreach ($objects as $object) {
            $document = $transformer->transform($object, $attributes);
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
        $attributes = $this->getIndexableAttributes($type);
        $document = $transformer->transform($object, $attributes);
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
     * @inheritdoc
     */
    public function getTransformer()
    {
        if (!$this->_transformer instanceof ObjectToDocumentTransformerInterface) {
            $this->_transformer = Yii::createObject($this->_transformer);
        }

        return $this->_transformer;
    }

    /**
     * Sets transformer instance or config.
     *
     * @param string|array|ObjectToDocumentTransformerInterface $transformer
     */
    public function setTransformer($transformer)
    {
        $this->_transformer = $transformer;
    }

    /**
     * Returns searchable type by index type.
     *
     * @param string $type
     * @return SearchableInterface
     */
    public function getSearchableType($type)
    {
        /** @var \im\search\components\SearchManager $search */
        $search = Yii::$app->get('search');

        return $search->getSearchableType($type);
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
     * Returns searchable attributes by index type.
     *
     * @param string $type
     * @return AttributeDescriptor[]
     */
    public function getIndexableAttributes($type)
    {
        return $this->getSearchableType($type)->getIndexableAttributes();
    }
}