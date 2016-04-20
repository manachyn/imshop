<?php

namespace im\search;

use im\base\routing\ModuleRulesTrait;
use im\base\types\EntityType;
use yii\base\BootstrapInterface;
use Yii;
use yii\web\Application;

/**
 * Class Bootstrap
 * @package im\search
 */
class Bootstrap implements BootstrapInterface
{
    use ModuleRulesTrait;

    /**
     * Module routing.
     *
     * @return array
     */
    public function getRules()
    {
        return [
            [
                'class' => 'yii\rest\UrlRule',
                'prefix' => 'api/v1',
                'extraPatterns' => [
                    'GET,HEAD suggest/<text:.+>' => 'suggest'
                ],
                'controller' => ['search' => 'search/rest/search']
            ]
        ];
    }

    /**
     * Event handlers.
     *
     * @var array
     */
    public $eventHandlers = [
        'im\search\components\EventsHandler'
    ];

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->registerTranslations($app);
        $this->addRules($app);
        $this->registerDefinitions();
        $this->registerSearchService($app);
        $this->registerEntityTypes($app);
        if ($app instanceof Application) {
            $this->registerPageTypes($app);
            $this->registerWidgets($app);
        }
        $this->registerEventHandlers();
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
     * Registers a class definitions in container.
     */
    public function registerDefinitions()
    {
        Yii::$container->set('im\search\models\SearchPage', [
            'as template' => [
                'class' => 'im\cms\components\TemplateBehavior'
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
        $cms->registerPageType(new EntityType('search_page', 'im\search\models\SearchPage'));
    }

    /**
     * Registers widgets.
     *
     * @param \yii\base\Application $app
     */
    public function registerWidgets($app)
    {
        /** @var \im\cms\components\LayoutManager $layoutManager */
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

    /**
     * Registers entity types.
     *
     * @param \yii\base\Application $app
     */
    public function registerEntityTypes($app)
    {
        /** @var \im\base\types\EntityTypesRegister $typesRegister */
        $typesRegister = $app->get('typesRegister');
        $typesRegister->registerEntityType(new EntityType('terms_facet', 'im\search\models\TermsFacet', 'facets', Module::t('facet', 'Terms facet')));
        $typesRegister->registerEntityType(new EntityType('range_facet', 'im\search\models\RangeFacet', 'facets', Module::t('facet', 'Range facet')));
        $typesRegister->registerEntityType(new EntityType('interval_facet', 'im\search\models\IntervalFacet', 'facets', Module::t('facet', 'Interval facet')));
        $typesRegister->registerEntityType(new EntityType('facet_term', 'im\search\models\FacetTerm', 'facet_values', Module::t('facet', 'Term')));
        $typesRegister->registerEntityType(new EntityType('facet_range', 'im\search\models\FacetRange', 'facet_values', Module::t('facet', 'Range')));
        $typesRegister->registerEntityType(new EntityType('searchable_types_facet', 'im\search\models\SearchableTypesFacet', 'facets', Module::t('facet', 'Searchable types facet')));
        $typesRegister->registerEntityType(new EntityType('searchable_types_facet_term', 'im\search\models\SearchableTypesFacetTerm', 'facet_values', Module::t('facet', 'Searchable types facet term')));
    }

    /**
     * Registers event handlers.
     */
    public function registerEventHandlers()
    {
        foreach ($this->eventHandlers as $key => $handler) {
            $this->eventHandlers[$key] = Yii::createObject($handler);
        }
    }
}