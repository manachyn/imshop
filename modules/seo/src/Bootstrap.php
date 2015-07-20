<?php

namespace im\seo;

use im\base\traits\ModuleTranslateTrait;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    use ModuleTranslateTrait;

    /**
     * @var string module messages category.
     */
    public static $messagesCategory = 'seo';

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
        $app->i18n->translations[static::$messagesCategory . '/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@im/seo/messages',
            'fileMap' => [
                static::$messagesCategory => 'module.php',
                static::$messagesCategory . '/meta' => 'meta.php'
            ]
        ];
    }
}