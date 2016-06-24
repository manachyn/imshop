<?php

namespace im\blog;

use im\base\types\EntityType;
use yii\base\Application;
use yii\base\BootstrapInterface;
use Yii;

/**
 * Class Bootstrap
 * @package im\blog
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
        $this->registerEntityTypes();
        if ($app instanceof \yii\web\Application) {
            $this->registerPageTypes($app);
            $this->registerWidgets($app);
        }
        $this->registerSearchableTypes($app);
        $this->setAliases();
    }

    /**
     * Register module translations.
     *
     * @param Application $app
     */
    public function registerTranslations($app)
    {
        $app->i18n->translations[Module::$messagesCategory . '/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@im/blog/messages',
            'fileMap' => [
                Module::$messagesCategory => 'module.php',
                Module::$messagesCategory . '/article' => 'article.php',
            ]
        ];
    }

    /**
     * Registers a class definitions in container.
     */
    public function registerDefinitions()
    {
        Yii::$container->set('im\blog\models\Article', [
            'as seo' => [
                'class' => 'im\seo\components\SeoBehavior',
                'metaClass' => 'im\blog\models\ArticleMeta',
                'ownerType' => 'article'
            ]
        ]);

        Yii::$container->set('im\blog\models\News', [
            'as seo' => [
                'class' => 'im\seo\components\SeoBehavior',
                'metaClass' => 'im\blog\models\ArticleMeta',
                'ownerType' => 'news'
            ]
        ]);
    }

    /**
     * Registers page types.
     *
     * @param \yii\base\Application $app
     */
    public function registerPageTypes($app)
    {
        /** @var \im\cms\components\Cms $cms */
        $cms = $app->get('cms');
        $cms->registerPageType(new EntityType('news_list_page', 'im\blog\models\NewsListPage'));
        $cms->registerPageType(new EntityType('articles_list_page', 'im\blog\models\ArticlesListPage'));
    }

    /**
     * Registers widgets.
     *
     * @param Application $app
     */
    public function registerWidgets($app)
    {
        $layoutManager = $app->get('layoutManager');
        $layoutManager->registerWidget('im\blog\widgets\LastNewsWidget');
        $layoutManager->registerWidget('im\blog\widgets\LastArticlesWidget');
    }

    /**
     * Registers entity types.
     */
    public function registerEntityTypes()
    {
        /** @var \im\base\types\EntityTypesRegister $typesRegister */
        $typesRegister = Yii::$app->get('typesRegister');
        $typesRegister->registerEntityType(new EntityType('article', 'im\blog\models\Article'));
        $typesRegister->registerEntityType(new EntityType('news', 'im\blog\models\News'));
        $typesRegister->registerEntityType(new EntityType('article_meta', 'im\blog\models\ArticleMeta'));
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
            'class' => 'im\blog\components\search\News',
            'type' => 'news',
            'modelClass' => 'im\blog\models\News',
            'searchServiceId' => 'db',
            'searchResultsView' => '@im/blog/frontend/views/news/_site_search_results',
        ]);
        $searchManager->registerSearchableType([
            'class' => 'im\blog\components\search\Article',
            'type' => 'article',
            'modelClass' => 'im\blog\models\Article',
            'searchServiceId' => 'db',
            'searchResultsView' => '@im/blog/frontend/views/article/_site_search_results',
        ]);
    }

    /**
     * @return void
     */
    public function setAliases()
    {
        Yii::setAlias('@im/blog', __DIR__);
    }
}

