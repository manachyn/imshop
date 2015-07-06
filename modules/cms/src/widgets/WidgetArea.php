<?php

namespace im\cms\widgets;

use im\cms\models\WidgetArea as WidgetAreaModel;
use im\cms\models\Widget as WidgetModel;
use yii\base\Widget;
use yii\caching\Cache;
use yii\caching\TagDependency;
use yii\db\ActiveRecord;
use Yii;

class WidgetArea extends Widget
{
    /**
     * @var string
     */
    public $code;

    /**
     * @var string
     */
    public $layout;

    /**
     * @var ActiveRecord
     */
    public $owner;

    /**
     * @var boolean whether to enable page caching.
     */
    public $enableCache = true;

    /**
     * @var WidgetModel[]
     */
    protected $_widgets;

    public function init()
    {
        parent::init();
        $this->setWidgets();
    }

    public function run()
    {
        if ($this->_widgets)
            foreach ($this->_widgets as $widget)
                if ($output = $widget->run())
                    echo "\n" . $output;

    }

    protected function setWidgets()
    {
        if ($this->enableCache) {
            /* @var $cache Cache */
            $cache = \Yii::$app->cache;
            $key = $this->getCacheKey();
            $widgets = $cache->get($key);
            if ($widgets === false) {
                $widgets = $this->loadWidgets();
                if ($widgets) {
                    $tags = [Yii::$app->layoutManager->getWidgetAreasCacheTag()];
                    if ($this->owner !== null)
                        $tags[] = Yii::$app->layoutManager->getWidgetAreasCacheTag($this->owner);
                    $dependency = new TagDependency(['tags' => $tags]);
                    $cache->set($key, $widgets, 0, $dependency);
                }
            }
            $this->_widgets = $widgets;
        }
        else
            $this->_widgets = $this->loadWidgets();
    }

    /**
     * @return \im\cms\models\Widget[]|array
     */
    protected function loadWidgets()
    {
        $widgets = [];
        $model = $this->loadModel();
        if ($model !== null) {
            if ($model->display == WidgetAreaModel::DISPLAY_INHERIT && $this->owner !== null
                && $this->owner->hasMethod('getParent')) {
                $model = $this->getInheritedModel($this->owner);
            }
        }
        if ($model !== null) {
            $widgets = $model->getWidgets();
        }

        return $widgets;
    }

    /**
     * @return \im\cms\models\WidgetArea|null
     */
    protected function loadModel()
    {
        $condition = ['code' => $this->code, 'layout_id' => $this->layout];
        if ($this->owner !== null) {
            $pks = $this->owner->primaryKey();
            $condition['owner_id'] = $this->owner->$pks[0];
        }
        return WidgetAreaModel::findOne($condition);
    }

    /**
     * @param ActiveRecord $owner
     * @return \im\cms\models\WidgetArea
     */
    protected function getInheritedModel($owner)
    {
        $widgetArea = null;
        $parent = $owner->getParent();
        if ($parent !== null) {
            $pks = $parent->primaryKey();
            $condition = ['code' => $this->code, 'layout_id' => $this->layout, 'owner_id' => $parent->$pks[0], 'owner_type' => Yii::$app->layoutManager->getOwnerType($parent)];
            /** @var WidgetAreaModel $widgetArea */
            $widgetArea = Yii::$app->layoutManager->getWidgetAreas()->where($condition)->one();
            if ($widgetArea !== null && $widgetArea->display == WidgetAreaModel::DISPLAY_INHERIT)
                $widgetArea = $this->getInheritedModel($parent);
        }
        else {
            $condition = ['code' => $this->code, 'layout_id' => $this->layout, 'owner_id' => 0];
            $widgetArea = Yii::$app->layoutManager->getWidgetAreas()->where($condition)->one();
        }
        return $widgetArea;
    }

    /**
     * Returns the cache key.
     * @return mixed the cache key
     */
    protected function getCacheKey()
    {
        $owner = 0;
        if ($this->owner !== null) {
            $pks = $this->owner->primaryKey();
            $owner = $this->owner->$pks[0];
        }
        return [
            __CLASS__,
            $this->code,
            $this->layout,
            $owner
        ];
    }

} 