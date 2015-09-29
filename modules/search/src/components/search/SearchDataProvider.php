<?php

namespace im\search\components\search;

use im\search\components\query\QueryInterface;
use im\search\components\transformer\DocumentToActiveRecordTransformer;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\BaseDataProvider;
use yii\data\Pagination;
use yii\db\ActiveQueryInterface;
use yii\web\Request;

class SearchDataProvider extends BaseDataProvider
{
    /**
     * @var QueryInterface the query that is used to fetch data models.
     */
    public $query;

    /**
     * @var string|callable the column that is used as the key of the data models.
     */
    public $key;

    /**
     * @inheritdoc
     */
    protected function prepareModels()
    {
        if (!$this->query instanceof QueryInterface) {
            throw new InvalidConfigException('The "query" property must be an instance of a class that implements the QueryInterface.');
        }
        if (($pagination = $this->getPagination()) !== false) {
            $pagination->setPage($this->getPaginationPage($pagination) - 1, false);
            $this->query->limit($pagination->getLimit())->offset($pagination->getOffset());
        }
        if (($sort = $this->getSort()) !== false) {
            $this->query->addOrderBy($sort->getOrders());
        }
        $result = $this->query->result();
        if ($pagination !== false) {
            $pagination->totalCount = $result->getTotal();
            $this->setTotalCount($result->getTotal());
        }

        return $result->getObjects();
    }

    /**
     * @inheritdoc
     */
    protected function prepareKeys($models)
    {
        $keys = [];
        if ($this->key !== null) {
            foreach ($models as $model) {
                if (is_string($this->key)) {
                    $keys[] = $model[$this->key];
                } else {
                    $keys[] = call_user_func($this->key, $model);
                }
            }

            return $keys;
        } elseif (($transformer = $this->query->getTransformer()) && $transformer instanceof DocumentToActiveRecordTransformer) {
            /* @var $class \yii\db\ActiveRecord */
            $class = $transformer->getObjectClass();
            $pks = $class::primaryKey();
            if (count($pks) === 1) {
                $pk = $pks[0];
                foreach ($models as $model) {
                    $keys[] = $model[$pk];
                }
            } else {
                foreach ($models as $model) {
                    $kk = [];
                    foreach ($pks as $pk) {
                        $kk[$pk] = $model[$pk];
                    }
                    $keys[] = $kk;
                }
            }

            return $keys;
        } else {
            return array_keys($models);
        }
    }

    /**
     * @inheritdoc
     */
    protected function prepareTotalCount()
    {
        if (!$this->query instanceof QueryInterface) {
            throw new InvalidConfigException('The "query" property must be an instance of a class that implements the QueryInterface.');
        }
        $query = clone $this->query;

        return (int) $query->limit(-1)->offset(-1)->orderBy([])->count('*');
    }

    protected function getPaginationPage(Pagination $pagination)
    {
        if (($params = $pagination->params) === null) {
            $request = Yii::$app->getRequest();
            $params = $request instanceof Request ? $request->getQueryParams() : [];
        }

        return isset($params[$pagination->pageParam]) ? $params[$pagination->pageParam] : 1;
    }
}