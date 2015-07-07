<?php
namespace app\modules\users;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\users\controllers';

    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $this->registerTranslations();

        $this->modules = [
            'backend' => [
                'class' => 'app\modules\users\backend\Module'
            ],
        ];
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/users/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@app/modules/users/messages',
            'fileMap' => [
                'modules/users/module' => 'module.php',
                'modules/users/user' => 'user.php',
                'modules/users/users' => 'users.php',
            ]
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/users/' . $category, $message, $params, $language);
    }
}
