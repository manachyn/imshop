<?php
namespace im\config;

use im\base\traits\ModuleTranslateTrait;
use Yii;

/**
 * Config module.
 *
 * @package im\config
 */
class Module extends \yii\base\Module
{
    use ModuleTranslateTrait;

    /**
     * @var string module messages category.
     */
    public static $messagesCategory = 'config';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'im\config\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    /**
     * Register module translations.
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations[static::$messagesCategory . '/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@im/config/messages',
            'fileMap' => [
                static::$messagesCategory => 'module.php'
            ]
        ];
    }

    /**
     * Translate a message to the specified language using module translations.
     *
     * @param string $category the message category.
     * @param string $message the message to be translated.
     * @param array $params the parameters that will be used to replace the corresponding placeholders in the message.
     * @param string $language the language code.
     * @return string the translated message.
     * @see \Yii::t()
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/' . $category, $message, $params, $language);
    }
}
