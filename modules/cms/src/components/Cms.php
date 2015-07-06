<?php

namespace im\cms\components;

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
     * @var array page types
     */
    public $pageTypes = [];


    public function registerPageType($type, $class)
    {
        $baseClass = Page::className();
        if (!is_subclass_of($class, $baseClass))
            throw new InvalidParamException("Class '$class' must extend '$baseClass'");
        $this->pageTypes[$type] = $class;
    }

    /**
     * @param Page|string $page page object or class name
     * @return string page type
     * @throws InvalidParamException
     */
    public function getPageType($page)
    {
        $pageClass = is_object($page) ? get_class($page) : $page;
        $pageType = null;
        foreach ($this->pageTypes as $type => $class) {
            if ($class == $pageClass) {
                $pageType = $type;
                break;
            }
        }
        if ($pageType === null)
            throw new InvalidParamException("Class '$pageClass' is not registered as page type");

        return $pageType;
    }

    public function getPageClass($type)
    {
        if (isset($this->pageTypes[$type]))
            return $this->pageTypes[$type];
        else
            throw new InvalidParamException("Page type '$type' is not registered");
    }
}