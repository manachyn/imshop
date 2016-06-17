<?php

namespace im\users;

use im\rbac\components\AuthDataProviderInterface;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\console\Application as ConsoleApplication;

/**
 * Class Bootstrap
 * @package im\users
 */
class Bootstrap implements BootstrapInterface, AuthDataProviderInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        $this->registerTranslations($app);
        $this->setAliases();

//        /** @var $module Module */
//        $module = $app->getModule('users');
//
//
//
//        if ($module) {
////            $this->registerTranslations();
////            if ($app instanceof ConsoleApplication) {
////                $module->controllerNamespace = 'im\user\commands';
////            } else {
////
////                $configUrlRule = [
////                    'prefix' => $module->urlPrefix,
////                    'rules'  => $module->urlRules
////                ];
////
////                if ($module->urlPrefix != 'user') {
////                    $configUrlRule['routePrefix'] = 'user';
////                }
////
////                $app->get('urlManager')->rules[] = new GroupUrlRule($configUrlRule);
////
////                if (!$app->has('authClientCollection')) {
////                    $app->set('authClientCollection', [
////                        'class' => Collection::className(),
////                    ]);
////                }
////            }
//        }
    }

//    /**
//     * Register module translations.
//     */
//    public function registerTranslations()
//    {
//        Yii::$app->i18n->translations['modules/users/*'] = [
//            'class' => 'yii\i18n\PhpMessageSource',
//            'sourceLanguage' => 'en-US',
//            'basePath' => '@im/users/src/messages',
//            'fileMap' => [
//                'modules/users/module' => 'module.php',
//                'modules/users/user' => 'user.php'
//            ]
//        ];
//    }

    /**
     * Register module translations.
     *
     * @param Application $app
     */
    public function registerTranslations($app)
    {
        $app->i18n->translations[Module::$messagesCategory . '/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en-US',
            'basePath' => '@im/users/messages',
            'fileMap' => [
                Module::$messagesCategory => 'module.php',
                Module::$messagesCategory . '/user' => 'user.php'
            ]
        ];
    }

    /**
     * @return void
     */
    public function setAliases()
    {
        Yii::setAlias('@im/users', __DIR__);
    }

    /**
     * @inheritdoc
     */
    public function getAuthItems()
    {
        $config = require(__DIR__ . '/config/rbac.php');

        return $config['items'];
    }

    /**
     * @inheritdoc
     */
    public function getAuthRules()
    {
        return [];
    }
}

