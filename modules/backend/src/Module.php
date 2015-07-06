<?php
namespace im\backend;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'im\backend\controllers';

    public $defaultRoute = 'dashboard';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/backend/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/backend/messages',
            'fileMap' => [
                'modules/backend/module' => 'module.php',
                'modules/backend/dashboard' => 'dashboard.php'
            ]
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/backend/' . $category, $message, $params, $language);
    }
}
