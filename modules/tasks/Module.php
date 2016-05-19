<?php

namespace app\modules\tasks;

use Yii;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\tasks\controllers';

    public function init()
    {
        parent::init();

        $this->registerTranslations();

        $this->modules = [
            'backend' => [
                'class' => 'app\modules\tasks\backend\Module'
            ]
        ];
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/tasks/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@im/tasks/messages',
            'fileMap' => [
                'modules/tasks/module' => 'module.php',
                'modules/tasks/task' => 'tasks.php'
            ]
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/tasks/' . $category, $message, $params, $language);
    }
}
