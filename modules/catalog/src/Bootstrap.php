<?php

namespace im\catalog;

use im\catalog\models\Category;
use im\catalog\models\Product;
use yii\base\Application;
use yii\base\BootstrapInterface;
use Yii;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->registerTranslations($app);
        $this->addRules($app);
        $this->registerDefinitions();
    }

    /**
     * Adds module rules.
     *
     * @param Application $app
     */
    public function addRules($app)
    {
        $app->getUrlManager()->addRules([
            [
                'class' => 'yii\rest\UrlRule',
                'prefix' => 'api/v1',
                'extraPatterns' => [
                    'GET,HEAD roots' => 'roots',
                    'GET,HEAD leaves' => 'leaves',
                    'GET,HEAD {id}/children' => 'children',
                    'GET,HEAD {id}/descendants' => 'descendants',
                    'GET,HEAD {id}/leaves' => 'leaves',
                    'GET,HEAD {id}/ancestors' => 'ancestors',
                    'GET,HEAD {id}/parent' => 'parent',
                    'PUT,PATCH {id}/move' => 'move',
                    'POST search' => 'search'
                ],
                'controller' => ['categories' => 'catalog/rest/category', 'product-categories' => 'catalog/rest/product-category']
            ],
            [
                'class' => 'yii\rest\UrlRule',
                'prefix' => 'api/v1',
                'extraPatterns' => [
                    'GET,HEAD {id}/attributes' => 'attributes'
                ],
                'controller' => ['product-types' => 'catalog/rest/product-type']
            ]
        ], false);
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
            'basePath' => '@im/catalog/messages',
            'fileMap' => [
                Module::$messagesCategory => 'module.php',
                Module::$messagesCategory . '/product' => 'product.php',
                Module::$messagesCategory . '/attribute' => 'attribute.php'
            ]
        ];
    }

    /**
     * Registers a class definitions in container.
     */
    public function registerDefinitions() {
        Yii::$container->set(Category::className(), [
            'as seo' => [
                'class' => 'im\seo\components\SeoBehavior',
                'metaClass' => 'im\catalog\models\CategoryMeta',
                'ownerType' => false
            ]
        ]);
        Yii::$container->set(Product::className(), [
            'as seo' => [
                'class' => 'im\seo\components\SeoBehavior',
                'metaClass' => 'im\catalog\models\ProductMeta',
                'ownerType' => false
            ]
        ]);
    }
}