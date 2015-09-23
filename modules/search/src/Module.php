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