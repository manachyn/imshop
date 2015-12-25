<?php

namespace im\seo;

use im\base\types\EntityType;
use im\seo\models\Meta;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\AfterSaveEvent;
use yii\web\View;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->registerTranslations($app);
        $this->registerEntityTypes();
        $this->registerEventHandlers($app);
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
            'basePath' => '@im/seo/messages',
            'fileMap' => [
                Module::$messagesCategory => 'module.php',
                Module::$messagesCategory . '/meta' => 'meta.php'
            ]
        ];
    }

    /**
     * Registers entity types.
     */
    public function registerEntityTypes()
    {
        /** @var \im\base\types\EntityTypesRegister $typesRegister */
        $typesRegister = Yii::$app->get('typesRegister');
        $typesRegister->registerEntityType(new EntityType('seo_meta', 'im\seo\models\Meta'));
        $typesRegister->registerEntityType(new EntityType('open_graph', 'im\seo\models\OpenGraph'));
        $typesRegister->registerEntityType(new EntityType('twitter_card', 'im\seo\models\TwitterCard'));
    }

    /**
     * Registers module event handlers.
     *
     * @param \yii\base\Application $app
     */
    public function registerEventHandlers($app)
    {
        $app->view->on(View::EVENT_BEGIN_PAGE, function (Event $event) use ($app) {
            /** @var \im\seo\components\Seo $seo */
            $seo = $app->get('seo');
            /** @var View $view */
            $view = $event->sender;
            $seo->applyContextMetaToView($view);
        });
        Event::on(Meta::className(), Meta::EVENT_AFTER_UPDATE, [$this, 'onMetaUpdate']);
    }

    /**
     * @param AfterSaveEvent $event
     */
    public function onMetaUpdate(AfterSaveEvent $event)
    {
        /** @var Meta $meta */
        $meta = $event->sender;
        /** @var \im\seo\components\Seo $seo */
        $seo = Yii::$app->get('seo');
        $cacheManager = $seo->getCacheManager();
        if ($cacheManager) {
            $cacheManager->deleteFromCacheByTags([$meta::className() . '::' . $meta->getPrimaryKey()]);
        }
    }
}