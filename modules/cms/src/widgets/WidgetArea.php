<?php

namespace im\cms\widgets;

use im\cms\models\Template;
use im\cms\models\widgets\WidgetArea as WidgetAreaModel;
use im\cms\models\widgets\Widget as WidgetModel;
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
     * @var Template
     */
    public $template;

    /**
     * @var mixed
     */
    public $context;

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
        if ($this->_widgets) {
            foreach ($this->_widgets as $widget) {
                $widget->context = $this->context;
                if ($output = $widget->run()) {
                    echo "\n" . $output;
                }
            }
        }
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
        } else {
            $this->_widgets = $this->loadWidgets();
        }
    }

    /**
     * @return \im\cms\models\widgets\Widget[]
     */
    protected function loadWidgets()
    {
        $widgets = [];
        $model = $this->loadModel();
        if ($model) {
            $widgets = $model->getWidgets()->all();
        }

        return $widgets;
    }

    /**
     * @return \im\cms\models\widgets\WidgetArea
     */
    protected function loadModel()
    {
        $condition = ['code' => $this->code, 'template_id' => $this->template->id];

        return WidgetAreaModel::findOne($condition);
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