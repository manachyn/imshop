<?php

namespace im\catalog;

use im\base\traits\ModuleTranslateTrait;
use Yii;

/**
 * Catalog module.
 *
 * @package im\catalog
 */
class Module extends \yii\base\Module
{
    use ModuleTranslateTrait;

    /**
     * @var string module messages category.
     */
    public static $messagesCategory = 'catalog';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'im\catalog\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->modules = [
            'backend' => [
                'class' => 'im\catalog\backend\Module'
            ],
            'rest' => [
                'class' => 'im\catalog\rest\Module'
            ],
        ];
    }
}
