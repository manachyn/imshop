<?php

namespace im\cms;

use im\base\types\EntityType;
use im\cms\models\Page;
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
        //$layoutManager->registerOwner('im\cms\models\Page', 'page');
        //$layoutManager->registerConfigurableComponent($this);
        $this->registerTranslations($app);
//        $this->addRules($app);
        $this->registerDefinitions();
        $this->registerEntityTypes();
        $this->registerPageTypes();
        $this->registerWidgets($app);
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
                Module::$messagesCategory . '/menu' => 'menu.php'
            ]
        ];
    }

    /**
     * Adds module rules.
     *
     * @param Application $app
     */
    public function addRules($app)
    {
        $app->getUrlManager()->addRules([
            //'<path>' => 'cms/page/view',
            //'<path:.+>' => 'cms/page/view',
            '' => 'cms/page/view',
            [
                'pattern' => '<path:.+>',
                'route' => 'cms/page/view',
                'suffix' => '.html',
            ],
            //'' => 'cms/default/index',
            '<_a:(about|contacts|captcha)>' => 'site/default/<_a>'
        ], false);
    }

    /**
     * Registers a class definitions in container.
     */
    public function registerDefinitions() {
        Yii::$container->set(Page::className(), [
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
        $layoutManager->registerWidget('im\cms\models\widgets\ContentWidget');
        $layoutManager->registerWidget('im\cms\models\widgets\BannerWidget');
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
}