<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute' => 'backend/dashboard',
    'bootstrap' => [
        'log' => 'log',
        'base' => 'im\base\Bootstrap',
        'adminlte' => 'im\adminlte\Bootstrap',
        'imshop' => 'im\imshop\backend\Bootstrap',
        'cms' => 'im\cms\backend\Bootstrap',
        'seo' => 'im\seo\backend\Bootstrap',
        'search' => 'im\search\backend\Bootstrap',
        'filesystem' => 'im\filesystem\Bootstrap'
    ],
    'modules' => [
        'backend' => 'im\backend\Module',
        'cms' => 'im\cms\backend\Module',
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
//        'request' => [
//            'baseUrl' => '/backend',
//        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        // Modules components
        'backendTheme' => 'im\adminlte\Theme',
        'seo' => 'im\seo\components\Seo',
    ],
    'params' => $params,
];
