<?php

namespace im\eav;

use Yii;
use yii\base\BootstrapInterface;

/**
 * Class Bootstrap
 * @package im\eav
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
            'basePath' => '@im/eav/messages',
            'fileMap' => [
                Module::$messagesCategory => 'module.php',
                Module::$messagesCategory . '/attribute' => 'attribute.php',
                Module::$messagesCategory . '/value' => 'value.php'
            ]
        ];
    }

    /**
     * @return void
     */
    public function setAliases()
    {
        Yii::setAlias('@im/eav', __DIR__);
    }
}