<?php

namespace im\cms\components;

use im\cms\models\Menu;
use im\cms\models\MenuItem;
use im\cms\models\widgets\Widget;
use im\cms\models\widgets\WidgetArea;
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
        $defaultLayout = $layouts ? $layouts[0] : null;
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

    /**
     * Returns menu by location.
     *
     * @param string $location
     * @return Menu|null
     */
    public function getMenu($location)
    {
        /** @var \im\cms\components\Cms $cms */
        $cms = Yii::$app->get('cms');
        $cacheManager = $cms->getCacheManager();
        if ($cacheManager) {
            $dataName = Menu::className();
            $cacheKey = [$dataName, $location];
            return $cacheManager->getFromCache($dataName, $cacheKey, function () use ($location) {
                return $this->loadMenu($location);
            });
        }

        return $this->loadMenu($location);
    }

    /**
     * Returns widget area by code and template.
     *
     * @param string $code
     * @param int $template
     * @return WidgetArea|null
     */
    public function getWidgetArea($code, $template = null)
    {
        /** @var \im\cms\components\Cms $cms */
        $cms = Yii::$app->get('cms');
        $cacheManager = $cms->getCacheManager();
        if ($cacheManager) {
            $dataName = WidgetArea::className();
            $cacheKey = [$dataName, $code, $template];
            return $cacheManager->getFromCache($dataName, $cacheKey, function () use ($code, $template) {
                return $this->loadWidgetArea($code, $template);
            });
        }

        return $this->loadWidgetArea($code, $template);
    }

    /**
     * Loads menu with items from db.
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

    /**
     * Loads widget area with items from db.
     *
     * @param string $code
     * @param int $template
     * @return WidgetArea|null
     */
    private function loadWidgetArea($code, $template = null)
    {
        $condition = ['code' => $code];
        $condition['template_id'] = $template;
        /** @var WidgetArea $widgetArea */
        $widgetArea = WidgetArea::findOne($condition);
        if ($widgetArea) {
            $widgets = $widgetArea->getWidgets()->all();
            $widgetArea->populateRelation('widgets', $widgets);
        }

        return $widgetArea;
    }
} 