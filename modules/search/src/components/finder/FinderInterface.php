<?php

namespace im\search\components\finder;

use im\search\components\query\QueryInterface;
use im\search\components\query\SearchQueryInterface;

/**
 * Interface FinderInterface.
 *
 * @package im\search\components\finder
 */
interface FinderInterface
{
    /**
     * Finds by index and type.
     *
     * @param string $index
     * @param string $type
     * @return QueryInterface
     */
    public static function find($index = null, $type = null);

    /**
     * Finds by search query, index and type.
     *
     * @param SearchQueryInterface $query
     * @param string $index
     * @param string $type
     * @return QueryInterface
     */
    public static function findByQuery(SearchQueryInterface $query, $index = null, $type = null);

//    public static function findOne($condition, $index = null, $type = null);
//
//    public static function findAll($condition, $index = null, $type = null);

    public static function findById($id, $index, $type, $options = []);

    public static function findByIds($ids, $index, $type, $options = []);

    public static function getTransformer($type);
}