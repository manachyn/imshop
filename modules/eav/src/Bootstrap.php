<?php

namespace im\eav;

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
     * @param \yii\base\Application $app
     */
    public function registerTranslations($app)
    {
        $app->i18n->translations[Module::$messagesCategory . '/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@im/eav/messages',
            'fileMap' => [
                Module::$messagesCategory => 'module.php',
                Module::$messagesCategory . '/attribute' => 'attribute.php',
                Module::$messagesCategory . '/value' => 'value.php'
            ]
        ];
    }
}