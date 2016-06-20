<?php

namespace im\cms\components;

use im\base\routing\ModelRouteResolver;
use im\cms\models\Page;
use Yii;
use yii\caching\Cache;
use yii\caching\TagDependency;

/**
 * Class PageModelRouteResolver resolves page route by attribute.
 * @package im\cms\components
 */
class PageModelRouteResolver extends ModelRouteResolver
{
    /**
     * @var string
     */
    public $childRoute;

    /**
     * @inheritdoc
     */
    public function resolve($params)
    {
        if (!isset($params['path'])) {
            return false;
        }
        /* @var $modelClass Page */
        $modelClass = $this->modelClass;
        $condition = ['path' => $params['path']];
        $parts = explode('/', $params['path']);
        //$pagePath = array_pop($parts);
        $urlManager = Yii::$app->getUrlManager();
        $match = false;
        if ($urlManager->cache instanceof Cache) {
            $cacheKey = array_merge([$this->modelClass], $condition);
            if (($match = $urlManager->cache->get($cacheKey)) === false) {
                foreach (array_reverse($parts) as $key => $slug) {
                    if ($match = $modelClass::findBySlug($slug)->asArray()->count()) {
                        if ($key > 0) {
                            $params = array_merge($params, [
                                'path' => implode('/', array_slice($parts, 0, -$key)),
                                'childPath' => implode('/', array_slice($parts, -$key))
                            ]);
                            $this->route = $this->childRoute;
                        }
                        $dependency = new TagDependency(['tags' => [$this->modelClass . '::' . $slug]]);
                        $urlManager->cache->set($cacheKey, $match, 0, $dependency);
                        break;
                    }
                }
            }
        } else {
            foreach (array_reverse($parts) as $key => $slug) {
                if ($match = $modelClass::findBySlug($slug)->asArray()->count()) {
                    if ($key > 0) {
                        $params = array_merge($params, [
                            'path' => implode('/', array_slice($parts, 0, -$key)),
                            'childPath' => implode('/', array_slice($parts, -$key))
                        ]);
                        $this->route = $this->childRoute;
                    }
                    break;
                }
            }
        }

        return $match ? [$this->getRoute(), $params] : false;
    }
}