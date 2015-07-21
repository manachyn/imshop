<?php

namespace im\seo;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->registerTranslations($app);
    }

    /**
     * Registers module translations.
     */
    public function registerTranslations($app)
    {
        $app->i18n->translations[Module::$messagesCategory . '/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@im/seo/messages',
            'fileMap' => [
                Module::$messagesCategory => 'module.php',
                Module::$messagesCategory . '/meta' => 'meta.php'
            ]
        ];
    }
}