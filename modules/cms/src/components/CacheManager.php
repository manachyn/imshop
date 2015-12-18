<?php

namespace im\cms\components;

use im\cms\models\Menu;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Yii;
use yii\base\Component;
use yii\base\InvalidParamException;
use yii\caching\Cache;
use yii\caching\TagDependency;

/**
 * Module cache manager.
 *
 * @package im\cms\components
 */
class CacheManager extends Component
{
    /**
     * @var \yii\caching\Cache[]
     */
    private $_caches = [
        'defaultCache' => 'cache'
    ];

    /**
     * @var string
     */
    private $_defaultCache = 'defaultCache';

    /**
     * Maps data class to cache name.
     *
     * @var array
     */
    private $_dataCaches = [];

    /**
     * Maps data class to cache key methods.
     *
     * @var array
     */
    private $_dataCacheKeys = [
        'im\cms\models\Menu' => 'getMenuCacheKey'
    ];

    /**
     * Returns cache for object.
     *
     * @param object $object
     * @return null|Cache
     */
    public function getCacheFor($object)
    {
        $cache = null;
        $dataName = get_class($object);
        if (isset($this->_dataCaches[$dataName])) {
            $cache = $this->getCache($this->_dataCaches[$dataName]);
        } else {
            $cache = $this->getDefaultCache();
        }

        return $cache;
    }

    /**
     * Returns cache for data.
     *
     * @param string $dataName
     * @return null|Cache
     */
    public function getCacheForData($dataName)
    {
        $cache = null;
        if (isset($this->_dataCaches[$dataName])) {
            $cache = $this->getCache($this->_dataCaches[$dataName]);
        } else {
            $cache = $this->getDefaultCache();
        }

        return $cache;
    }

    /**
     * Returns cache key for object.
     *
     * @param object $object
     * @return mixed
     */
    public function getKeyFor($object)
    {
        $dataName = get_class($object);
        if (isset($this->_dataCacheKeys[$dataName])) {
            return call_user_func([$this, $this->_dataCacheKeys[$dataName]], $object);
        } else {
            return null;
        }
    }

    /**
     * @return null|Cache
     */
    public function getDefaultCache()
    {
        return $this->getCache($this->_defaultCache);
    }

    /**
     * @param string $key
     * @return null|Cache
     */
    public function getCache($key)
    {
        if (!$this->_caches[$key] instanceof Cache) {
            if (is_string($this->_caches[$key])) {
                $this->_caches[$key] = Yii::$app->get($this->_caches[$key], false);
            } else {
                $this->_caches[$key] = Yii::createObject($this->_caches[$key]);
            }
        }

        return $this->_caches[$key];
    }

    /**
     * @return \yii\caching\Cache[]
     */
    public function getCaches()
    {
        foreach ($this->_caches as $key => $cache) {
            if ($cache instanceof Cache) {
                $this->_caches[$key] = $this->getCache($key);
            }
        }

        return $this->_caches;
    }

    /**
     * @param \yii\caching\Cache[] $caches
     */
    public function setCaches($caches)
    {
        $this->_caches = $caches;
    }

//    /**
//     * Invalidates cache.
//     *
//     * @param string $dataName
//     * @param string|array $tags
//     */
//    public function invalidateTaggedCache($object, $tags)
//    {
//        $cache = $this->getCacheFor($dataName);
//        if ($cache) {
//            TagDependency::invalidate($cache, $tags);
//        }
//    }

    /**
     * Delete object from cache.
     *
     * @param object $object
     */
    public function deleteFromCache($object)
    {
        if (($cache = $this->getCacheFor($object)) && ($key = $this->getKeyFor($object))) {
            $cache->delete($key);
        }
    }

    /**
     * @param object $object
     */
    public function onObjectChange($object)
    {
        $this->deleteFromCache($object);
    }

    /**
     * Returns menu cache key.
     *
     * @param Menu $menu
     * @return array
     */
    public function getMenuCacheKey(Menu $menu)
    {
        return [
            Menu::className(),
            $menu->location
        ];
    }
}