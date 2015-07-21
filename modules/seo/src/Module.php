<?php

namespace im\seo;

use im\base\traits\ModuleTranslateTrait;

class Module extends \yii\base\Module
{
    use ModuleTranslateTrait;

    /**
     * @var string module messages category.
     */
    public static $messagesCategory = 'seo';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'im\seo\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}
