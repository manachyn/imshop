<?php

namespace im\seo;

use im\base\types\EntityType;
use Yii;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->registerTranslations($app);
        $this->registerEntityTypes();
    }

    /**
     * Registers module translations.
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
}