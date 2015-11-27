<?php

namespace im\search\components\query;

use yii\base\Event;

class QueryResultEvent extends Event
{
    /**
     * @var QueryResultInterface
     */
    public $queryResult;
}