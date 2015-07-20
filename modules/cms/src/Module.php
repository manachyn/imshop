<?php

namespace im\cms;

use im\base\traits\ModuleTranslateTrait;
use Yii;

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
    public $controllerNamespace = 'im\cms\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->modules = [
            'backend' => [
                'class' => 'im\cms\backend\Module'
            ],
        ];
    }
}
