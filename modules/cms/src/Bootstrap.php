<?php

namespace im\cms;

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
        $layoutManager = Yii::$app->get('layoutManager');
        $layoutManager->registerWidgetClass('im\cms\models\ContentWidget');
        $layoutManager->registerWidgetClass('im\cms\models\BannerWidget');
        $layoutManager->registerOwnerClass('im\cms\models\Page', 'page');
        //$layoutManager->registerConfigurableComponent($this);
        $this->registerTranslations();
        $this->addRules($app);
        $this->registerDefinitions();
    }

    /**
     * Register module translations.
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations[Module::$messagesCategory . '/*'] = [
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
                'class' => 'app\modules\seo\components\SeoBehavior',
                'metaClass' => 'im\cms\models\PageMeta',
                'ownerType' => false
            ]
        ]);
    }
}