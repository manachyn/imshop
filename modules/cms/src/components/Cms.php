<?php

namespace im\cms\components;

use im\base\types\EntityType;
use im\cms\models\Page;
use yii\base\Component;
use Yii;
use yii\base\InvalidParamException;
use yii\caching\Cache;
use yii\caching\Dependency;

class Cms extends Component
{
    /**
     * @var Cache|string the cache object or the ID of the cache application component
     * that is used for page caching.
     * @see enablePageCache
     */
    public $pageCache = 'cache';

    /**
     * @var boolean whether to enable page caching.
     * Note that in order to enable page caching, a valid cache component as specified
     * by [[pageCache]] must be enabled and [[enablePageCache]] must be set true.
     * @see pageCache
     */
    public $enablePageCache = false;

    /**
     * @var integer number of seconds that the page data can remain valid in cache.
     * Use 0 to indicate that the cached data will never expire.
     */
    public $pageCacheDuration = 0;

    /**
     * @var array|Dependency the dependency that the cached page content depends on.
     * This can be either a [[Dependency]] object or a configuration array for creation the dependency object.
     */
    public $pageCacheDependency;

    /**
     * @var CacheManager
     */
    public $_cacheManager = 'im\cms\components\CacheManager';

    /**
     * Registers page type.
     *
     * @param EntityType $type
     */
    public function registerPageType($type)
    {
        $baseClass = Page::className();
        if ($type->getClass() !== $baseClass && !is_subclass_of($type->getClass(), $baseClass)) {
            throw new InvalidParamException("Class '{$type->getClass()}' must extend '$baseClass'");
        }
        $type->setGroup('page');
        Yii::$app->get('typesRegister')->registerEntityType($type);
    }

    /**
     * Returns registered page types.
     *
     * @return \im\base\types\EntityType[]
     */
    public function getPageTypes()
    {
        return Yii::$app->get('typesRegister')->getEntityTypes('page');
    }

    /**
     * Create page instance by type.
     *
     * @param string $type
     * @return Page
     */
    public function getPageInstance($type = null)
    {
        /** @var \im\base\types\EntityTypesRegister $typesRegister */
        $typesRegister = Yii::$app->get('typesRegister');
        $class = $type && $typesRegister->hasEntityType($type) ? $typesRegister->getEntityClass($type) : Page::className();

        return Yii::createObject(['class' => $class, 'type' => $type]);
    }

    /**
     * Returns cache manager.
     *
     * @return CacheManager|null
     */
    public function getCacheManager()
    {
        if ($this->_cacheManager === false) {
            return null;
        } elseif (!$this->_cacheManager instanceof CacheManager) {
            $this->_cacheManager = Yii::createObject($this->_cacheManager);
        }

        return $this->_cacheManager;
    }
}