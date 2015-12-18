<?php

namespace im\cms\components;

use im\cms\models\Menu;
use im\cms\models\MenuItem;
use Yii;
use yii\base\Component;
use yii\base\Event;
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