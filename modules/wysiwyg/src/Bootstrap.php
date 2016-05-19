<?php

namespace im\wysiwyg;

use im\config\components\ConfigManager;
use yii\base\Application;
use yii\base\BootstrapInterface;
use Yii;

/**
 * Class Bootstrap
 * @package im\wysiwyg
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->registerTranslations($app);
        $this->setAliases();
        $this->registerConfigs();
    }

    /**
     * @return void
     */
    public function registerConfigs()
    {
        /** @var ConfigManager $configManager */
        $configManager = Yii::$app->get('configManager');
        $configManager->registerConfig(new Config());
    }

    /**
     * Register module translations.
     *
     * @param Application $app
     */
    public function registerTranslations($app)
    {
        $app->i18n->translations[Module::$messagesCategory . '/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@im/wysiwyg/messages',
            'fileMap' => [
                Module::$messagesCategory => 'module.php'
            ]
        ];
    }

    /**
     * @return void
     */
    public function setAliases()
    {
        Yii::setAlias('@im/wysiwyg', __DIR__);
    }
}

