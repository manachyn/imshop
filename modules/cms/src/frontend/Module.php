<?php

namespace im\cms\frontend;

use im\base\traits\ModuleTranslateTrait;

/**
 * Class Module
 * @package im\cms\frontend
 */
class Module extends \yii\base\Module
{
    use ModuleTranslateTrait;

    /**
     * @var string module messages category.
     */
    public static $messagesCategory = 'cms';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'im\cms\frontend\controllers';
}
