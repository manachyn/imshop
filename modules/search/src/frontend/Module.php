<?php

namespace im\search\frontend;

use im\base\traits\ModuleTranslateTrait;

/**
 * Search module.
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
    public $controllerNamespace = 'im\search\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->modules = [
            'rest' => [
                'class' => 'im\search\rest\Module'
            ],
        ];
    }
}
