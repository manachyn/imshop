<?php

namespace im\catalog;

use im\base\types\EntityType;
use im\catalog\models\Product;
use im\catalog\models\ProductCategory;
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
        $this->registerEntityTypes($app);
        $this->registerSearchableTypes($app);
        if ($app instanceof \yii\web\Application) {
            $this->registerWidgets($app);
        }
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
    public function registerDefinitions()
    {
        Yii::$container->set(ProductCategory::className(), [
            'as seo' => [
                'class' => 'im\seo\components\SeoBehavior',
                'metaClass' => 'im\catalog\models\ProductCategoryMeta',
                'ownerType' => false
            ],
            'as template' => [
                'class' => 'im\cms\components\TemplateBehavior'
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

    /**
     * Registers widgets.
     *
     * @param Application $app
     */
    public function registerWidgets($app)
    {
        $layoutManager = $app->get('layoutManager');
        $layoutManager->registerWidget('im\catalog\models\widgets\ProductCategoriesList');
    }

    /**
     * Registers entity types.
     *
     * @param Application $app
     */
    public function registerEntityTypes($app)
    {
        /** @var \im\base\types\EntityTypesRegister $typesRegister */
        $typesRegister = $app->get('typesRegister');
        $typesRegister->registerEntityType(new EntityType('product', 'im\catalog\models\Product'));
        $typesRegister->registerEntityType(new EntityType('product_meta', 'im\catalog\models\ProductMeta'));
        $typesRegister->registerEntityType(new EntityType('category_meta', 'im\catalog\models\CategoryMeta'));
        $typesRegister->registerEntityType(new EntityType('product_category_meta', 'im\catalog\models\ProductCategoryMeta'));
    }

    /**
     * Registers searchable types.
     *
     * @param Application $app
     */
    public function registerSearchableTypes($app)
    {
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = $app->get('searchManager');
        $searchManager->registerSearchableType([
            'class' => 'im\search\components\service\db\IndexedSearchableType',
            'type' => 'product',
            'modelClass' => 'im\catalog\models\Product'
        ]);
//        $searchManager->registerSearchableType([
//            'class' => 'im\search\components\service\db\SearchableType',
//            'modelClass' => 'im\catalog\models\Product',
//            'type' => 'product',
//            'searchServiceId' => 'db'
//        ]);
    }
}