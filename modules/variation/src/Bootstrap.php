<?php

namespace im\variation;

use im\base\traits\ModuleTranslateTrait;
use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    use ModuleTranslateTrait;

    /**
     * @var string module messages category.
     */
    public static $messagesCategory = 'variation';

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->registerTranslations($app);
        $this->setAliases();
    }

    /**
     * Registers module translations.
     *
     * @param \yii\base\Application $app
     */
    public function registerTranslations($app)
    {
        $app->i18n->translations[static::$messagesCategory . '/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@im/variation/messages',
            'fileMap' => [
                static::$messagesCategory => 'module.php'
            ]
        ];
    }

    /**
     * @return void
     */
    public function setAliases()
    {
        Yii::setAlias('@im/variation', __DIR__);
    }
}