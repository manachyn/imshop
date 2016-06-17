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
        $pagePath = array_pop(explode('/', $params['path']));
        $urlManager = Yii::$app->getUrlManager();
        if ($urlManager->cache instanceof Cache) {
            $cacheKey = array_merge([$this->modelClass], $condition);
            if (($match = $urlManager->cache->get($cacheKey)) === false) {
                $match = $modelClass::findBySlug($pagePath)->asArray()->count();
                $dependency = new TagDependency(['tags' => [$this->modelClass . '::' . $pagePath]]);
                $urlManager->cache->set($cacheKey, $match, 0, $dependency);
            }
        } else {
            $match = $modelClass::findBySlug($pagePath)->asArray()->count();
        }

        return $match ? $this->getRoute() : false;
    }
}