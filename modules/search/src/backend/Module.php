<?php

namespace im\search\backend;

use im\base\traits\ModuleTranslateTrait;
use Yii;
use yii\console\Application;

/**
 * Search backend module.
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
     * @var string module messages category.
     */
    public $controllerNamespace = 'im\search\backend\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (Yii::$app instanceof Application) {
            $this->controllerNamespace = 'im\search\commands';
        }
        $this->registerTranslations();
    }

    /**
     * Registers module translations.
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations[Module::$messagesCategory . '/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@im/search/messages',
            'fileMap' => [
                Module::$messagesCategory => 'module.php',
                Module::$messagesCategory . '/index' => 'index.php'
            ]
        ];
    }
}
