<?php

namespace im\base\routing;
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
        // @TODO Add caching
        /* @var $modelClass ActiveRecordInterface */
        $modelClass = $this->modelClass;
        $condition = [];
        foreach ($this->paramsToAttributesMap as $param => $attribute) {
            if (isset($params[$param])) {
                $condition[$attribute] = $params[$param];
            } else {
                return false;
            }
        }

        return $modelClass::find()->where($condition)->count() ? $this->getRoute() : false;
    }

    /**
     * @inheritdoc
     */
    public function getRoute()
    {
        return $this->route;
    }
}