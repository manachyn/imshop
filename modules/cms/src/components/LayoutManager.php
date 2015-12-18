<?php

namespace im\cms\components;

use im\cms\models\Menu;
use im\cms\models\MenuItem;
use im\cms\models\widgets\Widget;
use im\tree\components\TreeHelper;
use yii\base\Component;
use Yii;
use yii\base\InvalidParamException;

/**
 * Class LayoutManager manages saving templates, widget areas and widgets.
 *
 * @package im\cms\components
 */
class LayoutManager extends Component
{

    public $widgetBaseClass = 'im\cms\models\widgets\Widget';

    /**
     * @var array|Layout[]
     */
    private $_layouts = [];

    /**
     * @var array
     */
    private $_widgets = [];

    /**
     * @var array|MenuLocation[]
     */
    public $_menuLocations = [];

    /**
     * Registers widget.
     *
     * @param string $class
     * @throws \yii\base\InvalidParamException
     */
    public function registerWidget($class)
    {
        if (!is_subclass_of($class, $this->widgetBaseClass)) {
            throw new InvalidParamException("Class $class must extend $this->widgetBaseClass");
        }
        $this->_widgets[] = $class;
    }

    /**
     * Registers layout.
     *
     * @param array|Layout $layout
     */
    public function registerLayout($layout)
    {
        $this->_layouts[] = $layout;
    }

    /**
     * Returns layouts.
     *
     * @return Layout[]
     */
    public function getLayouts()
    {
        foreach ($this->_layouts as $key => $layout) {
            if (!$layout instanceof Layout) {
                $this->_layouts[$key] = Yii::createObject($layout);
            }
        }

        return $this->_layouts;
    }

    /**
     * Returns layout by id.
     *
     * @param string $id
     * @return Layout|null
     */
    public function getLayout($id = '')
    {
        $layout = null;
        if ($id) {
            foreach ($this->getLayouts() as $item) {
                if ($item->id == $id) {
                    $layout = $item;
                    break;
                }
            }
        } else {
            $layout = $this->getDefaultLayout();
        }

        return $layout;
    }

    /**
     * Returns default layout.
     *
     * @return Layout
     */
    public function getDefaultLayout()
    {
        $layouts = $this->getLayouts();
        $defaultLayout = $layouts[0];
        foreach ($layouts as $layout) {
            if ($layout->default) {
                $defaultLayout = $layout;
            }
        }

        return $defaultLayout;
    }

    /**
     * Returns available widgets.
     *
     * @return Widget[]
     */
    public function getAvailableWidgets()
    {
        $widgets = [];
        foreach ($this->_widgets as $class) {
            $widgets[] = Yii::createObject($class);
        }

        return $widgets;
    }

    /**
     * Creates widget by it's type.
     *
     * @param string $type
     * @return Widget|null
     */
    public function getWidgetInstance($type)
    {
        foreach ($this->_widgets as $class) {
            /** @var Widget $class */
            if ($class::getType() === $type) {
                return $widget = Yii::createObject($class);
            }
        }

        return null;
    }

    /**
     * @return MenuLocation[]
     */
    public function getMenuLocations()
    {
        return $this->_menuLocations;
    }

    /**
     * @param array|MenuLocation $menuLocation
     */
    public function registerMenuLocation($menuLocation)
    {
        $this->_menuLocations[] = $menuLocation;
    }

    public function getMenu($location)
    {
        /** @var \im\cms\components\Cms $cms */
        $cms = Yii::$app->get('cms');
        $cacheManager = $cms->getCacheManager();
        if ($cacheManager) {
            $dataName = Menu::className();
            $cache = $cacheManager->getCacheForData($dataName);
            if ($cache) {
                $cacheKey = [$dataName, $location];
                if (($menu = $cache->get($cacheKey)) !== false) {
                    return $menu;
                } else {
                    $menu = $this->loadMenu($location);
                    $cache->set($cacheKey, $menu);
                    return $menu;
                }
            }
        }
    }

    /**
     * Loads menu with items for db.
     *
     * @param string $location
     * @return Menu
     */
    private function loadMenu($location)
    {
        /** @var Menu $menu */
        $menu = Menu::find()->where(['location' => $location])->one();
        $items = $menu->getItems()->where(['status' => MenuItem::STATUS_ACTIVE])->with(['icon', 'activeIcon', 'video'])->all();
        if ($items) {
            $items = TreeHelper::buildNodesTree($items);
            $menu->populateRelation('items', $items);
        }

        return $menu;
    }

//    /**
//     * @param ActiveRecord $owner
//     */
//    public function invalidateWidgetAreasCache($owner = null)
//    {
//        /* @var $cache Cache */
//        $cache = \Yii::$app->cache;
//        TagDependency::invalidate($cache, $this->getWidgetAreasCacheTag($owner));
//    }

//    /**
//     * Returns the cache tag name.
//     * This allows to invalidate all cached widget areas.
//     * @param ActiveRecord $owner
//     * @return string the cache tag name
//     */
//    public function getWidgetAreasCacheTag($owner = null)
//    {
//        return md5(serialize([
//            'WidgetArea',
//            $owner !== null ? $this->getOwnerType($owner) : ''
//        ]));
//    }
} 