<?php

namespace im\eav;

use im\base\traits\ModuleTranslateTrait;
use Yii;

/**
 * EAV module.
 *
 * @package im\eav
 */
class Module extends \yii\base\Module
{
    use ModuleTranslateTrait;

    /**
     * @var string module messages category.
     */
    public static $messagesCategory = 'eav';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'im\eav\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    }
}
