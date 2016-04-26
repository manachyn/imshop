<?php

namespace im\base\routing;

use Yii;
use yii\web\UrlRule;

class GroupUrlRule extends UrlRule
{
    /**
     * @inheritdoc
     */
    public $route = 'site/view';

    /**
     * @var RouteResolverInterface[] route resolvers
     */
    public $resolvers;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        foreach ($this->resolvers as $key => $resolver) {
            if (!$resolver instanceof RouteResolverInterface) {
                $this->resolvers[$key] = Yii::createObject($resolver);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();
        $controller = $pathInfo ? Yii::$app->createController($pathInfo) : false;
        if (!$controller) {
            list(, $params) = parent::parseRequest($manager, $request);
            foreach ($this->resolvers as $resolver) {
                if ($route = $resolver->resolve($params)) {
                    return [$route, $params];
                }
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function createUrl($manager, $route, $params)
    {
        $defaultRoute = $this->route;
        foreach ($this->resolvers as $resolver) {
            $this->route = $resolver->getRoute();
            if ($url = parent::createUrl($manager, $route, $params)) {
                return $url;
            }
        }
        $this->route = $defaultRoute;

        return parent::createUrl($manager, $route, $params);
    }
}