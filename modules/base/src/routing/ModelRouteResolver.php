<?php

namespace im\base\routing;
use Yii;
use yii\caching\Cache;
use yii\db\ActiveRecordInterface;
use yii\base\Object;
use yii\base\InvalidConfigException;

/**
 * Class ModelRouteResolver resolve model route by attribute.
 * @package im\base\routing
 */
class ModelRouteResolver extends Object implements RouteResolverInterface
{
    /**
     * @var string
     */
    public $route;

    /**
     * @var string
     */
    public $modelClass;

    /**
     * @var array
     */
    public $paramsToAttributesMap = ['path' => 'slug'];

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->route === null) {
            throw new InvalidConfigException('ModelRouteResolver::route must be set.');
        }
        if ($this->modelClass === null) {
            throw new InvalidConfigException('ModelRouteResolver::modelClass must be set.');
        }
    }

    /**
     * @inheritdoc
     */
    public function resolve($params)
    {
        /* @var $modelClass ActiveRecordInterface */
        $modelClass = $this->modelClass;
        $condition = $this->getCondition($params);
        if ($condition === false) {
            return false;
        }
        $urlManager = Yii::$app->getUrlManager();
        if ($urlManager->cache instanceof Cache) {
            $cacheKey = array_merge([$this->modelClass], $condition);
            if (($match = $urlManager->cache->get($cacheKey)) === false) {
                $match = $modelClass::find()->where($condition)->asArray()->count();
                $urlManager->cache->set($cacheKey, $match);
            }
        } else {
            $match = $modelClass::find()->where($condition)->asArray()->count();
        }

        return $match ? $this->getRoute() : false;
    }

    /**
     * @inheritdoc
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Maps params to condition.
     *
     * @param $params
     * @return array|bool
     */
    protected function getCondition($params)
    {
        $condition = [];
        foreach ($this->paramsToAttributesMap as $param => $attribute) {
            if (isset($params[$param])) {
                $condition[$attribute] = $params[$param];
            } else {
                return false;
            }
        }

        return $condition;
    }
}