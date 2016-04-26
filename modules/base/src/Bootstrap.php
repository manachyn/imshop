<?php

namespace im\base;

use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
//        $vendorDir = Yii::getAlias('@app');
//        Yii::setAlias('@im/adminlte', $vendorDir . '/themes/adminlte/src');
//        Yii::setAlias('@im/imshop', $vendorDir . '/themes/imshop/src');
//        Yii::setAlias('@im/base', $vendorDir . '/modules/base/src');
//        Yii::setAlias('@im/cms', $vendorDir . '/modules/cms/src');
//        Yii::setAlias('@im/catalog', $vendorDir . '/modules/catalog/src');
//        Yii::setAlias('@im/backend', $vendorDir . '/modules/backend/src');
//        Yii::setAlias('@im/eav', $vendorDir . '/modules/eav/src');
//        Yii::setAlias('@im/variation', $vendorDir . '/modules/variation/src');
//        Yii::setAlias('@im/filesystem', $vendorDir . '/modules/filesystem/src');
//        Yii::setAlias('@im/elfinder', $vendorDir . '/modules/elfinder/src');
//        Yii::setAlias('@im/seo', $vendorDir . '/modules/seo/src');
//        Yii::setAlias('@im/tree', $vendorDir . '/modules/tree/src');
//        Yii::setAlias('@im/users', $vendorDir . '/modules/users/src');
//        Yii::setAlias('@im/search', $vendorDir . '/modules/search/src');
//        Yii::setAlias('@im/ecommerce', $vendorDir . '/modules/ecommerce/src');

        $this->setAliases();
        $this->registerTranslations($app);
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
            'basePath' => '@im/base/messages',
            'fileMap' => [
                Module::$messagesCategory => 'module.php',
                Module::$messagesCategory . '/relation-widget' => 'relation-widget.php'
            ]
        ];
    }

    public function setAliases()
    {
        Yii::setAlias('@im/base', __DIR__);
    }
}