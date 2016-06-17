<?php

namespace im\blog;

use im\base\traits\ModuleTranslateTrait;

/**
 * Class Module
 * @package im\blog
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
    public $controllerNamespace = 'im\blog\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->modules = [
            'backend' => [
                'class' => 'im\blog\backend\Module'
            ]
        ];
    }
}

