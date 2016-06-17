<?php

namespace im\cms\components;

use im\cms\models\Menu;
use im\cms\models\MenuItem;
use im\cms\models\Page;
use im\cms\models\widgets\WidgetArea;
use Yii;
use yii\base\Component;
use yii\base\Event;
use yii\caching\Cache;
use yii\caching\TagDependency;
use yii\db\AfterSaveEvent;

class CmsBackendEventsHandler extends Component
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        Event::on(Menu::className(), Menu::EVENT_AFTER_UPDATE, [$this, 'onMenuUpdate']);
        Event::on(MenuItem::className(), Menu::EVENT_AFTER_UPDATE, [$this, 'onMenuItemUpdate']);
        Event::on(Page::className(), Page::EVENT_AFTER_UPDATE, [$this, 'onPageUpdate']);
        Event::on(WidgetArea::className(), Page::EVENT_AFTER_UPDATE, [$this, 'onWidgetAreaUpdate']);
    }

    /**
     * @param AfterSaveEvent $event
     */
    public function onMenuUpdate(AfterSaveEvent $event)
    {
        /** @var Menu $menu */
        $menu = $event->sender;
        $this->onMenuChange($menu);
    }

    /**
     * @param AfterSaveEvent $event
     */
    public function onMenuItemUpdate(AfterSaveEvent $event)
    {
        /** @var MenuItem $menuItem */
        $menuItem = $event->sender;
        if ($menu = $menuItem->getMenu()) {
            $this->onMenuChange($menu);
        }
    }

    /**
     * @param AfterSaveEvent $event
     */
    public function onPageUpdate(AfterSaveEvent $event)
    {
        /** @var Page $page */
        $page = $event->sender;
        $this->invalidateCache([Page::className() . '::' . $page->getPrimaryKey()]);
        $this->invalidateUrlManagerCache([Page::className() . '::' . $page->getOldAttribute('slug')]);
    }

    /**
     * @param AfterSaveEvent $event
     */
    public function onWidgetAreaUpdate(AfterSaveEvent $event)
    {
        /** @var WidgetArea $widgetArea */
        $widgetArea = $event->sender;
        $this->invalidateCache([WidgetArea::className() . '::' . $widgetArea->getPrimaryKey()]);
    }

    /**
     * Invalidate tagged cache.
     *
     * @param array $tags
     * @throws \yii\base\InvalidConfigException
     */
    private function invalidateCache(array $tags)
    {
        /** @var \im\cms\components\Cms $cms */
        $cms = Yii::$app->get('cms');
        $cacheManager = $cms->getCacheManager();
        if ($cacheManager) {
            $cacheManager->deleteFromCacheByTags($tags);
        }
    }

    /**
     * Invalidate url manager tagged cache.
     *
     * @param array $tags
     */
    private function invalidateUrlManagerCache(array $tags)
    {
        $urlManager = Yii::$app->getUrlManager();
        if ($urlManager->cache instanceof Cache) {
            TagDependency::invalidate($urlManager->cache, $tags);
        }
    }

    /**
     * @param Menu $menu
     */
    private function onMenuChange(Menu $menu)
    {
        /** @var \im\cms\components\Cms $cms */
        $cms = Yii::$app->get('cms');
        $cacheManager = $cms->getCacheManager();
        if ($cacheManager) {
            $cacheManager->onObjectChange($menu);
        }
    }
}