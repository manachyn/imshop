<?php
namespace im\backend;

use im\base\traits\ModuleTranslateTrait;
use Yii;

class Module extends \yii\base\Module
{
    use ModuleTranslateTrait;

    /**
     * @var string module messages category.
     */
    public static $messagesCategory = 'backend';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'im\backend\controllers';

    /**
     * @inheritdoc
     */
    public $defaultRoute = 'dashboard';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    /**
     * Registers module translations.
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations[static::$messagesCategory . '/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@im/backend/messages',
            'fileMap' => [
                static::$messagesCategory => 'module.php',
                static::$messagesCategory .'/dashboard' => 'dashboard.php'
            ]
        ];
    }
}
