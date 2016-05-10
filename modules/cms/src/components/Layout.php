<?php

namespace im\cms\components;

use im\cms\models\widgets\WidgetArea;
use yii\base\Object;
use Yii;

class Layout extends Object
{
    /**
     * @var string an ID that uniquely identifies layout.
     */
    public $id;

    /**
     * @var string layout name.
     */
    public $name;

    /**
     * @var boolean whether the layout is default
     */
    public $default = false;

    /**
     * @var WidgetArea[]
     */
    public $_widgetAreas;

    /**
     * @var array|WidgetAreaDescriptor[]
     */
    public $_availableWidgetAreas = [];

    /**
     * @return \im\cms\models\widgets\WidgetArea[]
     */
    public function getWidgetAreas()
    {
        if (!$this->_widgetAreas) {
            foreach ($this->getAvailableWidgetAreas() as $key => $widgetArea) {
                $this->_widgetAreas[$key] = new WidgetArea([
                    'code' => $widgetArea->code,
                    'title' => $widgetArea->title,
                    'display' => WidgetArea::DISPLAY_ALWAYS
                ]);
            }
        }

        return $this->_widgetAreas;
    }

    /**
     * @param \im\cms\models\widgets\WidgetArea[] $widgetAreas
     */
    public function setWidgetAreas($widgetAreas)
    {
        $availableWidgetAreas = $this->getAvailableWidgetAreas();
        foreach($widgetAreas as $key => $widgetArea) {
            foreach ($availableWidgetAreas as $availableWidgetArea) {
                if ($availableWidgetArea->code == $widgetArea->code) {
                    $widgetAreas[$key]->title = $availableWidgetArea->title;
                    break;
                }
            }
        }
        $this->_widgetAreas = $widgetAreas;
    }

    /**
     * @return WidgetAreaDescriptor[]
     */
    public function getAvailableWidgetAreas()
    {
        foreach ($this->_availableWidgetAreas as $key => $widgetArea) {
            if (!$widgetArea instanceof WidgetAreaDescriptor) {
                $widgetArea = Yii::createObject($widgetArea);
                $this->_availableWidgetAreas[$key] = $widgetArea;
            }
        }

        return $this->_availableWidgetAreas;
    }

    /**
     * @param array|WidgetAreaDescriptor[] $availableWidgetAreas
     */
    public function setAvailableWidgetAreas($availableWidgetAreas)
    {
        $this->_availableWidgetAreas = $availableWidgetAreas;
    }
}