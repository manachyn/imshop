<?php

namespace im\elasticsearch\components;

use im\search\components\finder\BaseFinder;
use im\search\components\query\IndexQueryInterface;
use im\search\components\query\SearchQueryInterface;
use Yii;
use yii\elasticsearch\Connection;

/**
 * Elastic search finder implementation.
 *
 * @package im\elasticsearch\components
 */
class Finder extends BaseFinder
{
    /**
     * @inheritdoc
     */
    public function find($type)
    {
        /** @var IndexQueryInterface $query */
        $query = Yii::createObject([
            'class' => Query::className(),
            'index' => $this->getIndex($type),
            'type' => $type
        ]);

        if ($transformer = $this->getTransformer($type)) {
            $query->setTransformer($transformer);
        }

        return $query;
    }

    /**
     * @inheritdoc
     */
    public function findByQuery($type, SearchQueryInterface $query)
    {
        $query = Yii::createObject([
            'class' => Query::className(),
            'searchQuery' => $query,
            'index' => $this->getIndex($type),
            'type' => $type
        ]);

        if ($transformer = static::getTransformer($type)) {
            $query->setTransformer($transformer);
        }

        return $query;
    }

    public static function findById($id, $index, $type, $options = [])
    {
//        $command = static::getDb()->createCommand();
//        $result = $command->get($index, $type, $id, $options);
//        if ($result['found']) {
//            $model = static::instantiate($result);
//            static::populateRecord($model, $result);
//            $model->afterFind();
//
//            return $model;
//        }
//
//        return null;
    }

    public static function findByIds($ids, $index, $type, $options = [])
    {
//        if (empty($primaryKeys)) {
//            return [];
//        }
//        if (count($primaryKeys) === 1) {
//            $model = static::get(reset($primaryKeys));
//            return $model === null ? [] : [$model];
//        }
//
//        $command = static::getDb()->createCommand();
//        $result = $command->mget(static::index(), static::type(), $primaryKeys, $options);
//        $models = [];
//        foreach ($result['docs'] as $doc) {
//            if ($doc['found']) {
//                $model = static::instantiate($doc);
//                static::populateRecord($model, $doc);
//                $model->afterFind();
//                $models[] = $model;
//            }
//        }
//
//        return $models;
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
     * Returns index for type.
     * @param string $type
     * @return string
     */
    protected function getIndex($type)
    {
        return $this->getSearchableType($type)->getIndex()->getName();
    }

    /**
     * Returns transformer for type.
     *
     * @param string $type
     * @return \im\search\components\transformer\DocumentToObjectTransformerInterface
     */
    protected function getTransformer($type)
    {
        return $this->getSearchableType($type)->getDocumentToObjectTransformer();
    }
}