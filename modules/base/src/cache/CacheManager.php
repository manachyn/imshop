<?php

namespace im\base\cache;

use Yii;
use yii\base\Component;
use yii\caching\Cache;
use yii\caching\TagDependency;

/**
 * Class CacheManager
 * @package im\base\cache
 */
class CacheManager extends Component
{
    /**
     * @var \yii\caching\Cache[]
     */
    public $caches = [
        'defaultCache' => 'cache'
    ];

    /**
     * @var string
     */
    public $defaultCache = 'defaultCache';

    /**
     * Maps data class to cache name.
     *
     * @var array
     */
    protected $dataCaches = [];

    /**
     * Maps data class to cache key methods.
     *
     * @var array
     */
    protected $dataCacheKeys = [];

    /**
     * Maps data class to cache tags methods.
     *
     * @var array
     */
    protected $dataCacheTags = [];

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
        if (isset($this->dataCaches[$dataName])) {
            $cache = $this->getCache($this->dataCaches[$dataName]);
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
        if (isset($this->dataCaches[$dataName])) {
            $cache = $this->getCache($this->dataCaches[$dataName]);
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
        if (isset($this->dataCacheKeys[$dataName])) {
            return call_user_func([$this, $this->dataCacheKeys[$dataName]], $object);
        } else {
            return null;
        }
    }

    /**
     * Returns cache tags for object.
     *
     * @param object $object
     * @return mixed
     */
    public function getTagsFor($object)
    {
        $dataName = get_class($object);
        if (isset($this->dataCacheTags[$dataName])) {
            return call_user_func([$this, $this->dataCacheTags[$dataName]], $object);
        } else {
            foreach ($this->dataCacheTags as $key => $tags) {
                if (is_subclass_of($dataName, $key)) {
                    return call_user_func([$this, $this->dataCacheTags[$key]], $object);
                }
            }
            return null;
        }
    }

    /**
     * @return null|Cache
     */
    public function getDefaultCache()
    {
        return $this->getCache($this->defaultCache);
    }

    /**
     * @param string $key
     * @return null|Cache
     */
    public function getCache($key)
    {
        if (!$this->caches[$key] instanceof Cache) {
            if (is_string($this->caches[$key])) {
                $this->caches[$key] = Yii::$app->get($this->caches[$key], false);
            } else {
                $this->caches[$key] = Yii::createObject($this->caches[$key]);
            }
        }

        return $this->caches[$key];
    }

    /**
     * @return \yii\caching\Cache[]
     */
    public function getCaches()
    {
        foreach ($this->caches as $key => $cache) {
            if (!$cache instanceof Cache) {
                $this->caches[$key] = $this->getCache($key);
            }
        }

        return $this->caches;
    }

    /**
     * @param \yii\caching\Cache[] $caches
     */
    public function setCaches($caches)
    {
        $this->caches = $caches;
    }

    /**
     * Returns data from cache by key.
     *
     * @param string $dataName
     * @param array $key
     * @param \Closure $cacheMissCallback
     * @return mixed|null
     */
    public function getFromCache($dataName, $key, \Closure $cacheMissCallback = null)
    {
        $data = null;
        $cache = $this->getCacheForData($dataName);
        if ($cache && ($data = $cache->get($key)) !== false) {
            return $data;
        } elseif ($cacheMissCallback && ($data = call_user_func($cacheMissCallback))) {
            $this->addToCache($key, $data);
        }

        return $data;
    }

    /**
     * Adds object to cache.
     *
     * @param array $key
     * @param object $object
     */
    public function addToCache($key, $object)
    {
        if ($object && $cache = $this->getCacheFor($object)) {
            if ($tags = $this->getTagsFor($object)) {
                $dependency = new TagDependency(['tags' => $tags]);
                $cache->set($key, $object, 0, $dependency);
            } else {
                $cache->set($key, $object);
            }
        }
    }

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
        if ($tags = $this->getTagsFor($object)) {
            foreach ($this->getCaches() as $cache) {
                TagDependency::invalidate($cache, $tags);
            }
        }
    }

    /**
     * Invalidate cache by tags.
     *
     * @param array $tags
     */
    public function deleteFromCacheByTags(array $tags)
    {
        foreach ($this->getCaches() as $cache) {
            TagDependency::invalidate($cache, $tags);
        }
    }

    /**
     * @param object $object
     */
    public function onObjectChange($object)
    {
        $this->deleteFromCache($object);
    }
}