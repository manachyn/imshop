<?php

namespace im\search\backend;

use im\base\traits\ModuleTranslateTrait;

/**
 * Search backend module.
 *
 * @package im\search\backend
 */
class Module extends \yii\base\Module
{
    use ModuleTranslateTrait;

    /**
     * @var string module messages category.
     */
    public static $messagesCategory = 'search';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'im\search\backend\controllers';
}
