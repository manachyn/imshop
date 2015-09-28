<?php

namespace im\search;

use im\base\traits\ModuleTranslateTrait;
use Yii;
use yii\console\Application;

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
        if (Yii::$app instanceof Application) {
            $this->controllerNamespace = 'im\search\commands';
        }
        $this->modules = [
            'backend' => [
                'class' => 'im\search\backend\Module'
            ]
        ];
    }
}
