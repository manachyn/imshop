<?php

namespace im\filesystem;

use im\base\routing\ModuleRulesTrait;
use yii\base\BootstrapInterface;

/**
 * Class Bootstrap
 * @package im\filesystem
 */
class Bootstrap implements BootstrapInterface
{
    use ModuleRulesTrait;

    /**
     * Module routing.
     *
     * @return array
     */
    public function getRules()
    {
        return [
            ['class' => 'yii\rest\UrlRule', 'prefix' => 'api/v1', 'controller' => ['filesystems' => 'filesystem/rest/filesystem']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->registerTranslations($app);
        $this->addRules($app);
    }

    /**
     * Registers module translations.
     *
     * @param \yii\base\Application $app
     */
    public function registerTranslations($app)
    {
        $app->i18n->translations[Module::$messagesCategory . '/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@im/filesystem/messages',
            'fileMap' => [
                Module::$messagesCategory => 'module.php'
            ]
        ];
    }
}