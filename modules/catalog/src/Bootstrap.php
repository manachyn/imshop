<?php

namespace im\catalog;

use im\base\types\EntityType;
use yii\base\Application;
use yii\base\BootstrapInterface;
use Yii;

/**
 * Class Bootstrap
 * @package im\catalog
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->registerTranslations($app);
        $this->registerDefinitions();
        $this->registerEntityTypes($app);
        $this->registerSearchableTypes($app);
        if ($app instanceof \yii\web\Application) {
            $this->registerWidgets($app);
        }
        $this->setAliases();
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
        Yii::$container->set('im\catalog\models\ProductCategory', [
            'as seo' => [
                'class' => 'im\seo\components\SeoBehavior',
                'metaClass' => 'im\catalog\models\ProductCategoryMeta',
                'ownerType' => false
            ],
            'as template' => [
                'class' => 'im\cms\components\TemplateBehavior'
            ],
            'as search' => [
                'class' => 'im\search\components\SearchBehavior'
            ]
        ]);
        Yii::$container->set('im\catalog\models\Product', [
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
        $typesRegister->registerEntityType(new EntityType('categories_facet', 'im\catalog\models\CategoriesFacet', 'facets', Module::t('facet', 'Categories facet')));
        $typesRegister->registerEntityType(new EntityType('product_categories_facet', 'im\catalog\models\ProductCategoriesFacet', 'facets', Module::t('facet', 'Product categories facet')));
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
//        $searchManager->registerSearchableType([
//            'class' => 'im\catalog\components\search\IndexedProduct',
//            'type' => 'product',
//            'modelClass' => 'im\catalog\models\Product',
//            'default' => true,
//            'objectToDocumentTransformer' => 'im\elasticsearch\components\ActiveRecordToElasticDocumentTransformer',
//            'searchResultsView' => '@im/catalog/views/product/_site_search_results'
//        ]);
        $searchManager->registerSearchableType([
            'class' => 'im\catalog\components\search\Product',
            'modelClass' => 'im\catalog\models\Product',
            'type' => 'product',
            'searchServiceId' => 'db',
            'searchResultsView' => '@im/catalog/views/product/_site_search_results'
        ]);
        $searchManager->registerSearchableType([
            'class' => 'im\catalog\components\search\ProductCategory',
            'type' => 'product_category',
            'modelClass' => 'im\catalog\models\ProductCategory',
            'searchServiceId' => 'db',
            'searchResultsView' => '@im/catalog/views/product-category/_site_search_results'
        ]);
    }

    /**
     * @return void
     */
    public function setAliases()
    {
        Yii::setAlias('@im/catalog', __DIR__);
    }
}
