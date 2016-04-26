<?php

namespace im\cms\components;

use yii\base\Object;

class WidgetAreaDescriptor extends Object
{
    /**
     * @var string an ID that uniquely identifies widget area.
     */
    public $code;

    /**
     * @var string
     */
    public $title;
}