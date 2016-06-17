<?php

namespace im\rbac;

use im\base\traits\ModuleTranslateTrait;
use Yii;
use yii\console\Application;

/**
 * Class Module
 * @package im\rbac
 */
class Module extends \yii\base\Module
{
    use ModuleTranslateTrait;

    /**
     * @var string module messages category.
     */
    public static $messagesCategory = 'rbac';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'im\rbac\controllers';

    /**
     * @var array
     */
    public $authDataProviders = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (Yii::$app instanceof Application) {
            $this->controllerNamespace = 'im\rbac\commands';
        }
        $this->registerTranslations();
    }

    /**
     * Register module translations.
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations[Module::$messagesCategory . '/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@im/rbac/messages',
            'fileMap' => [
                Module::$messagesCategory => 'module.php'
            ]
        ];
    }
}

