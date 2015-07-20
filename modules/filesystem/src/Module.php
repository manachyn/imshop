<?php

namespace im\filesystem;

use im\base\traits\ModuleTranslateTrait;
use Yii;

class Module extends \yii\base\Module
{
    use ModuleTranslateTrait;

    /**
     * @var string module messages category.
     */
    public static $messagesCategory = 'filesystem';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'im\filesystem\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    /**
     * Registers module translations.
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations[static::$messagesCategory . '/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@im/filesystem/messages',
            'fileMap' => [
                static::$messagesCategory => 'module.php'
            ]
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/filesystem/' . $category, $message, $params, $language);
    }
}
