<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log' => 'log',
        'base' => 'im\base\Bootstrap',
        'cms' => 'im\cms\frontend\Bootstrap',
        'imshop' => 'im\imshop\Bootstrap',
        'search' => 'im\search\frontend\Bootstrap'
    ],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'cms' => 'im\cms\frontend\Module',
        'search' => 'im\search\frontend\Module',
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
            //'cache' => 'memCache',
            'rules' => require(__DIR__ . '/rules.php')
        ],
        'view' => [
            'theme' => ['class' => 'im\imshop\Theme']
        ],
        'assetManager' => [
            'bundles' => require(dirname(__DIR__) . '/assets/assets.php'),
        ],
    ],
    'params' => $params,
];
