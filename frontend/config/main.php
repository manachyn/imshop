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
        'search' => 'im\search\frontend\Bootstrap'
    ],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'cms' => 'im\cms\frontend\Module',
        'search' => 'im\search\frontend\Module',
        'users' => 'im\users\Module'
    ],
    'components' => [
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
            'theme' => ['class' => 'im\pkbnt\components\Theme']
        ],
        'assetManager' => [
            'bundles' => require(dirname(dirname(__DIR__)) . '/modules/pkbnt/src/components/assets/assets.php'),
        ],
        'glide' => [
            'class' => 'im\image\glide\Glide',
            'signKey' => 'akqTelFIql',
            'servers' => [
                'local' => [
                    'source' => '@frontend/web/files',
                    'cache' => '@frontend/runtime/cache/glide'
                ],
                's3' => [
                    'filesystem' => 's3',
                    'cache' => '@frontend/runtime/cache/glide'
                ]
            ]
        ],
    ],
    'controllerMap' => [
        'glide' => 'im\image\glide\controllers\GlideController'
    ],
    'params' => $params,
];
