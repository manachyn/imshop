<?php

namespace im\search\components\finder;

use im\search\components\query\QueryInterface;

interface FinderInterface
{
    /**
     * @return QueryInterface
     */
    public static function find($index = null, $type = null);

//    public static function findOne($condition, $index = null, $type = null);
//
//    public static function findAll($condition, $index = null, $type = null);

    public static function findById($id, $index, $type, $options = []);

    public static function findByIds($ids, $index, $type, $options = []);

    public static function getTransformer($type);
}