<?php

namespace im\elasticsearch\components\index;

use im\search\components\index\BaseIndexer;
use im\search\components\index\BulkResponse;
use im\search\components\index\Document;
use im\search\components\index\IndexActionResult;
use im\search\components\index\IndexInterface;
use im\search\components\index\Response as BaseResponse;
use im\search\components\searchable\AttributeDescriptor;
use yii\elasticsearch\Connection;
use yii\helpers\Json;

class Indexer extends BaseIndexer
{
    /**
     * @inheritdoc
     */
    public function insertDocument(Document $document, array $options = [])
    {
        $response = static::getDb()->createCommand()->insert(
            $document->getIndex(),
            $document->getType(),
            $document->getData(),
            $document->getId(),
            $options
        );

        return new Response('index', $response);
    }

    /**
     * @inheritdoc
     */
    public function insertDocuments($documents, array $options = [])
    {
        $bulk = '';
        foreach ($documents as $document) {
            $action = Json::encode([
                'index' => [
                    '_index' => $document->getIndex(),
                    '_type' => $document->getType(),
                    '_id' => (int) $document->getId(),
                ],
            ]);
            $data = Json::encode($document->getData());
            $bulk .= $action . "\n" . $data . "\n";
        }
        $url = ['_bulk'];
        $response = static::getDb()->post($url, [], $bulk);
        $responses = [];
        foreach ($response['items'] as $item) {
            $data = reset($item);
            $action = key($item);
            $responses[] = new Response($action, $data);
        }

        return new BulkResponse('index', $response, $responses);
    }

    /**
     * @inheritdoc
     */
    public function delete(IndexInterface $index, $type = null, $id = null, array $options = [])
    {
        $response = static::getDb()->createCommand()->delete(
            $index->getName(),
            $type,
            $id,
            $options
        );

        return new Response('delete', $response);
    }

    /**
     * @inheritdoc
     */
    public function deleteById(IndexInterface $index, $type, $ids, array $options = [])
    {
        $bulk = '';
        foreach ($ids as $id) {
            $bulk .= Json::encode([
                    'delete' => [
                        '_index' => $index->getName(),
                        '_type' => $type,
                        '_id' => $id
                    ],
                ]) . "\n";
        }

        $url = [$index->getName(), $type, '_bulk'];
        $response = static::getDb()->post($url, [], $bulk);
        $responses = [];
        foreach ($response['items'] as $item) {
            $data = reset($item);
            $action = key($item);
            $responses[] = new Response($action, $data);
        }

        return new BulkResponse('delete', $response, $responses);
    }

    /**
     * @inheritdoc
     */
    public function setMapping(IndexInterface $index, $type, array $attributes)
    {
        static::getDb()->createCommand()->deleteIndex($index->getName());
        $mapping = ['properties' => []];
        foreach ($attributes as $attribute) {
            if ($attribute->type || !empty($attribute->params['fullTextSearch'])) {
                $mapping['properties'][$attribute->name] = ['type' => $this->getMappingType($attribute->type)];
            }
            if (isset($mapping['properties'][$attribute->name]['type'])
                && $mapping['properties'][$attribute->name]['type'] == 'string'
                && empty($attribute->params['fullTextSearch'])) {
                $mapping['properties'][$attribute->name]['index'] = 'not_analyzed';
            }
            if (!empty($attribute->params['suggestions'])) {
                $mapping['properties'][$attribute->name . '_suggest'] = ['type' => 'completion'];
            }
        }
        $configuration = ['mappings' => [$type => $mapping]];
        static::getDb()->createCommand()->createIndex($index->getName(), $configuration);
    }

    /**
     * @inheritdoc
     */
    public function typeExists(IndexInterface $index, $type)
    {
        return static::getDb()->createCommand()->typeExists($index->getName(), $type);
    }

    /**
     * @inheritdoc
     */
    public function getIndexActionResult(BaseResponse $response)
    {
        $result = new IndexActionResult($response->getAction());
        if ($response instanceof BulkResponse) {
            if ($response->isSuccess()) {
                $result->success = count($response->getResponses());
            } else {
                foreach ($response->getResponses() as $item) {
                    if ($item->isSuccess()) {
                        $result->success++;
                    } elseif($item->getResponse()['status'] === 404) {
                        $result->notFounded++;
                    } else {
                        $result->error++;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Returns the database connection used.
     * @return Connection
     */
    public static function getDb()
    {
        return \Yii::$app->get('elasticsearch');
    }


    /**
     * @param string $type
     * @return string
     */
    private function getMappingType($type)
    {
        switch ($type) {
            case AttributeDescriptor::TYPE_STRING:
                $mappingType = 'string';
                break;
            case AttributeDescriptor::TYPE_INTEGER:
                $mappingType = 'integer';
                break;
            case AttributeDescriptor::TYPE_FLOAT:
                $mappingType = 'float';
                break;
            case AttributeDescriptor::TYPE_BOOLEAN:
                $mappingType = 'boolean';
                break;
            default:
                $mappingType = 'string';
        }

        return $mappingType;
    }
}