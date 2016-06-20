<?php

namespace im\blog\frontend;

use im\base\traits\ModuleTranslateTrait;

/**
 * Class Module
 * @package im\blog\frontend
 */
class Module extends \yii\base\Module
{
    use ModuleTranslateTrait;

    /**
     * @var string module messages category.
     */
    public static $messagesCategory = 'blog';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'im\blog\frontend\controllers';
}
