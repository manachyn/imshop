<?php

namespace im\cms\components;

use im\cms\models\widgets\Widget;
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
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        foreach ($this->_layouts as $key => $layout) {
            if (!$layout instanceof Layout) {
                $this->_layouts[$key] = Yii::createObject($layout);
            }
        }
    }

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
     * Registers widget.
     *
     * @param Layout $layout
     */
    public function registerLayout($layout)
    {
        $this->_layouts[] = $layout;
    }

    /**
     * Sets layouts.
     *
     * @param Layout[] $layouts
     */
    public function setLayouts($layouts)
    {
        $this->_layouts = $layouts;
    }

    /**
     * Returns layouts.
     *
     * @return Layout[]
     */
    public function getLayouts()
    {
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
            foreach ($this->_layouts as $item) {
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
        $defaultLayout = $this->_layouts[0];
        foreach ($this->_layouts as $layout) {
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