<?php

namespace im\elasticsearch\components\index;

use im\search\components\index\BaseIndexer;
use im\search\components\index\BulkResponse;
use im\search\components\index\Document;
use im\search\components\index\IndexActionResult;
use im\search\components\index\IndexInterface;
use im\search\components\index\Response as BaseResponse;
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
}