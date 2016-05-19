<?php
namespace app\modules\rbac;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\rbac\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $this->registerTranslations();

        $this->modules = [
            'backend' => [
                'class' => 'app\modules\rbac\backend\Module'
            ],
        ];
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/rbac'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@im/rbac/messages',
            'fileMap' => [
                'modules/rbac' => 'rbac.php'
            ]
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/' . $category, $message, $params, $language);
    }
}
