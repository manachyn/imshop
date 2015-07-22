<?php

namespace im\filesystem;

use im\base\traits\ModuleTranslateTrait;
use Yii;

class Module extends \yii\base\Module
{
    use ModuleTranslateTrait;

    /**
     * @var string module messages category.
     */
    public static $messagesCategory = 'filesystem';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'im\filesystem\controllers';
}
