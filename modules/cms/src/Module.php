<?php

namespace im\cms;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'im\cms\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $this->registerTranslations();

        $this->modules = [
            'backend' => [
                'class' => 'im\cms\backend\Module'
            ],
        ];
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/cms/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/cms/messages',
            'fileMap' => [
                'modules/cms/module' => 'module.php',
                'modules/cms/pages' => 'pages.php',
                'modules/cms/menu' => 'menu.php'
            ],
//
//
//            'class' => 'yii\i18n\PhpMessageSource',
//            'basePath' => "@app/messages",
//            'sourceLanguage' => 'en_US',
//            'fileMap' => array(
//                'app'=>'app.php'
//            )
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/cms/' . $category, $message, $params, $language);
    }
}
