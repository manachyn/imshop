<?php

namespace im\cms\components;

use yii\base\Object;

class MenuLocation extends Object
{
    /**
     * @var string an ID that uniquely identifies menu location.
     */
    public $code;

    /**
     * @var string
     */
    public $name;
}