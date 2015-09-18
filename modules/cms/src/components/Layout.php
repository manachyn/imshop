<?php

namespace im\cms\components;

use im\cms\models\widgets\WidgetArea;
use im\cms\Module;
use yii\base\Object;
use Yii;

class Layout extends Object
{
    /**
     * @var string layout name.
     */
    private $_name;

    /**
     * @var string an ID that uniquely identifies layout.
     */
    public $id;

    /**
     * @var boolean whether the layout is default
     */
    public $default = false;

    /**
     * @var WidgetArea[]
     */
    public $widgetAreas;

    /**
     * @var array|WidgetAreaDescriptor[]
     */
    private $_availableWidgetAreas;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        foreach ($this->_availableWidgetAreas as $key => $widgetArea) {
            if (!$widgetArea instanceof WidgetAreaDescriptor) {
                $widgetArea = Yii::createObject($widgetArea);
                $this->_availableWidgetAreas[$key] = $widgetArea;
            }
            $this->widgetAreas[$key] = new WidgetArea([
                'code' => $widgetArea->code,
                'title' => $widgetArea->title,
                'display' => WidgetArea::DISPLAY_ALWAYS
            ]);
        }
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->default ? $this->_name . ' (' . Module::t('module', 'default') . ')' : $this->_name;
    }

    /**
     * @param WidgetAreaDescriptor[] $availableWidgetAreas
     */
    public function setAvailableWidgetAreas($availableWidgetAreas)
    {
        $this->_availableWidgetAreas = $availableWidgetAreas;
    }

    /**
     * @return WidgetAreaDescriptor[]
     */
    public function getAvailableWidgetAreas()
    {
        return $this->_availableWidgetAreas;
    }


}