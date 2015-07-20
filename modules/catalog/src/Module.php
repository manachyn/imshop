<?php

namespace im\catalog;

use im\base\traits\ModuleTranslateTrait;
use Yii;

/**
 * Catalog module.
 *
 * @package im\catalog
 */
class Module extends \yii\base\Module
{
    use ModuleTranslateTrait;

    /**
     * @var string module messages category.
     */
    public static $messagesCategory = 'catalog';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'im\catalog\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
        $this->modules = [
            'backend' => [
                'class' => 'im\catalog\backend\Module'
            ],
            'rest' => [
                'class' => 'im\catalog\rest\Module'
            ],
        ];
    }

    /**
     * Registers module translations.
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations[static::$messagesCategory . '/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@im/catalog/messages',
            'fileMap' => [
                static::$messagesCategory => 'module.php',
                static::$messagesCategory . '/product' => 'product.php',
                static::$messagesCategory . '/attribute' => 'attribute.php'
            ]
        ];
    }


}
