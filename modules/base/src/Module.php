<?php

namespace im\base;

use im\base\traits\ModuleTranslateTrait;
use Yii;

class Module extends \yii\base\Module
{
    use ModuleTranslateTrait;

    /**
     * @var string module messages category.
     */
    public static $messagesCategory = 'base';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'im\base\controllers';
}
