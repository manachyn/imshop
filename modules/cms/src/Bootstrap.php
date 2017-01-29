<?php

namespace im\cms;

use im\base\types\EntityType;
use im\cms\frontend\forms\FeedbackForm;
use yii\base\Application;
use yii\base\BootstrapInterface;
use Yii;

/**
 * Class Bootstrap
 * @package im\cms
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
        $this->registerPageTypes();
        $this->registerWidgets($app);
        $this->registerSearchableTypes($app);
        $this->registerForms($app);
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
            'basePath' => '@im/cms/messages',
            'fileMap' => [
                Module::$messagesCategory => 'module.php',
                Module::$messagesCategory . '/page' => 'page.php',
                Module::$messagesCategory . '/menu' => 'menu.php',
                Module::$messagesCategory . '/feedback-request' => 'feedback-request.php'
            ]
        ];
    }

    /**
     * Registers a class definitions in container.
     */
    public function registerDefinitions()
    {
        Yii::$container->set('im\cms\models\Page', [
            'as seo' => [
                'class' => 'im\seo\components\SeoBehavior',
                'metaClass' => 'im\cms\models\PageMeta',
                'ownerType' => false
            ],
            'as template' => [
                'class' => 'im\cms\components\TemplateBehavior'
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
        $layoutManager->registerWidget('im\cms\widgets\ContentWidget');
        $layoutManager->registerWidget('im\cms\widgets\BannerWidget');
        $layoutManager->registerWidget('im\cms\widgets\MenuWidget');
        $layoutManager->registerWidget('im\cms\widgets\FormWidget');
        $layoutManager->registerWidget('im\cms\widgets\GalleryWidget');
    }

    /**
     * Registers entity types.
     */
    public function registerEntityTypes()
    {
        /** @var \im\base\types\EntityTypesRegister $typesRegister */
        $typesRegister = Yii::$app->get('typesRegister');
        $typesRegister->registerEntityType(new EntityType('page_meta', 'im\cms\models\PageMeta'));
    }

    /**
     * Registers page types.
     */
    public function registerPageTypes()
    {
        /** @var \im\cms\components\Cms $cms */
        $cms = Yii::$app->get('cms');
        $cms->registerPageType(new EntityType('page', 'im\cms\models\Page', 'page', 'Static page'));
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
//            'class' => 'im\search\components\service\db\IndexedSearchableType',
//            'type' => 'page',
//            'searchResultsView' => '@im/cms/views/page/_site_search_results',
//            'modelClass' => 'im\cms\models\Page',
//            'objectToDocumentTransformer' => 'im\elasticsearch\components\ActiveRecordToElasticDocumentTransformer',
//        ]);
        $searchManager->registerSearchableType([
            'class' => 'im\cms\components\search\Page',
            'type' => 'page',
            'modelClass' => 'im\cms\models\Page',
            'searchServiceId' => 'db',
            'searchResultsView' => '@im/cms/frontend/views/page/_site_search_results',
        ]);
    }

    /**
     * Register forms
     *
     * @param Application $app
     */
    public function registerForms($app)
    {
        /** @var \im\forms\components\FormsManager $formsManager */
        $formsManager = $app->get('formsManager');
        $formsManager->registerForm('feedback', FeedbackForm::class);
    }

    /**
     * @return void
     */
    public function setAliases()
    {
        Yii::setAlias('@im/cms', __DIR__);
    }
}
