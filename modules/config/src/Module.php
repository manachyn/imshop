<?php
namespace im\config;

use im\base\traits\ModuleTranslateTrait;
use Yii;

/**
 * Config module.
 *
 * @package im\config
 */
class Module extends \yii\base\Module
{
    use ModuleTranslateTrait;

    /**
     * @var string module messages category.
     */
    public static $messagesCategory = 'config';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'im\config\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    /**
     * Register module translations.
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations[static::$messagesCategory . '/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@im/config/messages',
            'fileMap' => [
                static::$messagesCategory => 'module.php'
            ]
        ];
    }
}
