<?php

namespace im\cms\backend\widgets;

use im\cms\models\Widget;
use yii\base\InvalidConfigException;
use yii\jui\Draggable;

class AvailableWidget extends Draggable
{
    /**
     * @var Widget widget model to display
     */
    public $widget;

    /**
     * @inheritdoc
     */
    public function init()
    {
        if ($this->widget === null) {
            throw new InvalidConfigException('The "widget" property must be set.');
        }

        $this->initOptions();

        parent::init();

        echo $this->render('available_widget', ['widget' => $this->widget]);
    }

    /**
     * Initializes the widget options.
     * This method sets the default values for various options.
     */
    protected function initOptions()
    {
        $this->options = array_merge([
            'class' => 'available-widget',
            'data-type' => $this->widget->getType()
        ], $this->options);

        $this->clientOptions = array_merge([
            'helper' => 'clone',
            'cursor' => 'move'
            //'containment' => 'document'
        ], $this->clientOptions);
    }
} 