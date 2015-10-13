<?php

namespace im\search;

use im\base\types\EntityType;
use yii\base\BootstrapInterface;
use Yii;
use yii\web\Application;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->registerTranslations($app);
        $this->registerSearchService($app);
        if ($app instanceof Application) {
            $this->registerPageTypes($app);
            $this->registerWidgets($app);
        }
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
            'basePath' => '@im/search/messages',
            'fileMap' => [
                Module::$messagesCategory => 'module.php',
                Module::$messagesCategory . '/index' => 'index.php'
            ]
        ];
    }

    /**
     * Registers page types.
     *
     * @param \yii\base\Application $app
     */
    public function registerPageTypes($app)
    {
        $cms = $app->get('cms');
        $cms->registerPageType(new EntityType('search_page', 'im\search\models\SearchPage'));
    }

    /**
     * Registers widgets.
     *
     * @param \yii\base\Application $app
     */
    public function registerWidgets($app)
    {
        $layoutManager = $app->get('layoutManager');
        $layoutManager->registerWidget('im\search\widgets\FacetsWidget');
    }

    /**
     * Registers search service.
     *
     * @param \yii\base\Application $app
     */
    public function registerSearchService($app)
    {
        /** @var \im\search\components\SearchManager $searchManager */
        $searchManager = $app->get('searchManager');
        $searchManager->registerSearchService('db', [
            'class' => 'im\search\components\service\db\SearchService',
            'name' => 'Database'
        ]);
    }
}