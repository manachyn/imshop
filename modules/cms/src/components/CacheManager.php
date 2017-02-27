<?php

namespace im\cms\components;

use im\cms\models\Menu;
use im\cms\models\Page;
use im\cms\models\widgets\WidgetArea;

/**
 * Module cache manager.
 *
 * @package im\cms\components
 */
class CacheManager extends \im\base\cache\CacheManager
{
    /**
     * @inheritdoc
     */
    protected $dataCacheKeys = [
        'im\cms\models\Menu' => 'getMenuCacheKey'
    ];

    /**
     * @inheritdoc
     */
    protected $dataCacheTags = [
        'im\cms\models\Page' => 'getPageCacheTags',
        'im\cms\models\widgets\WidgetArea' => 'getWidgetAreaCacheTags'
    ];

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
            'location' => $menu->location
        ];
    }

    /**
     * Returns page cache tags.
     *
     * @param Page $page
     * @return array
     */
    public function getPageCacheTags(Page $page)
    {
        return [
            Page::className(),
            Page::className() . '::' . $page->getPrimaryKey()
        ];
    }

    /**
     * Returns widget area cache tags.
     *
     * @param WidgetArea $widgetArea
     * @return array
     */
    public function getWidgetAreaCacheTags(WidgetArea $widgetArea)
    {
        return [
            WidgetArea::className(),
            WidgetArea::className() . '::' . $widgetArea->getPrimaryKey()
        ];
    }
}