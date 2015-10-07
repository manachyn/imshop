<?php

namespace im\variation;

use im\base\traits\ModuleTranslateTrait;
use Yii;

class Module extends \yii\base\Module
{
    use ModuleTranslateTrait;

    /**
     * @var string module messages category.
     */
    public static $messagesCategory = 'variation';

    /**
     * @var string module messages category.
     */
    public $controllerNamespace = 'im\variation\controllers';

    /**
     * @var string module messages category.
     */
    public function init()
    {
        parent::init();
    }
}
