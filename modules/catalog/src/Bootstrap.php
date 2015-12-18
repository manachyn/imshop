<?php

namespace im\catalog;

use im\base\types\EntityType;
use im\catalog\models\Product;
use im\catalog\models\ProductCategory;
use yii\base\Application;
use yii\base\BootstrapInterface;
use Yii;
use yii\base\InvalidConfigException;
use yii\caching\Cache;
use yii\web\UrlRule;
use yii\web\UrlRuleInterface;

class Bootstrap implements BootstrapInterface
{
    /**
     * Module routing.
     *
     * @var array
     */
    public $rules = [
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
    ];

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
        $urlManager = $app->getUrlManager();
        if ($urlManager->cache instanceof Cache) {
            $cacheKey = __CLASS__;
            $hash = md5(json_encode($this->rules));
            if (($data = $urlManager->cache->get($cacheKey)) !== false && isset($data[1]) && $data[1] === $hash) {
                $this->rules = $data[0];
            } else {
                $this->rules = $this->buildRules($this->rules, $urlManager);
                $urlManager->cache->set($cacheKey, [$this->rules, $hash]);
            }
        } else {
            $this->rules = $this->buildRules($this->rules, $urlManager);
        }
        $urlManager->addRules($this->rules, false);
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
        $searchManager->registerSearchableType([
            'class' => 'im\catalog\components\search\Product',
            'type' => 'product',
            'modelClass' => 'im\catalog\models\Product'
        ]);
        $searchManager->registerSearchableType([
            'class' => 'im\catalog\components\search\ProductCategory',
            'type' => 'product_category',
            'modelClass' => 'im\catalog\models\ProductCategory'
        ]);
//        $searchManager->registerSearchableType([
//            'class' => 'im\search\components\service\db\SearchableType',
//            'modelClass' => 'im\catalog\models\Product',
//            'type' => 'product',
//            'searchServiceId' => 'db'
//        ]);
    }

    /**
     * Builds URL rule objects from the given rule declarations.
     * @param array $rules the rule declarations. Each array element represents a single rule declaration.
     * Please refer to [[rules]] for the acceptable rule formats.
     * @param \yii\web\UrlManager $urlManager
     * @return \yii\web\UrlRuleInterface[] the rule objects built from the given rule declarations
     * @throws InvalidConfigException if a rule declaration is invalid
     */
    protected function buildRules($rules, $urlManager)
    {
        $compiledRules = [];
        $verbs = 'GET|HEAD|POST|PUT|PATCH|DELETE|OPTIONS';
        foreach ($rules as $key => $rule) {
            if (is_string($rule)) {
                $rule = ['route' => $rule];
                if (preg_match("/^((?:($verbs),)*($verbs))\\s+(.*)$/", $key, $matches)) {
                    $rule['verb'] = explode(',', $matches[1]);
                    // rules that do not apply for GET requests should not be use to create urls
                    if (!in_array('GET', $rule['verb'])) {
                        $rule['mode'] = UrlRule::PARSING_ONLY;
                    }
                    $key = $matches[4];
                }
                $rule['pattern'] = $key;
            }
            if (is_array($rule)) {
                $rule = Yii::createObject(array_merge($urlManager->ruleConfig, $rule));
            }
            if (!$rule instanceof UrlRuleInterface) {
                throw new InvalidConfigException('URL rule class must implement UrlRuleInterface.');
            }
            $compiledRules[] = $rule;
        }

        return $compiledRules;
    }
}