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
    'bootstrap' => [
        'log',
        'im\base\Bootstrap',
        'im\cms\backend\Bootstrap',
        'im\seo\backend\Bootstrap',
    ],
    'modules' => [
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
        'typesRegister' => 'im\base\types\EntityTypesRegister',
        'cms' => 'im\cms\components\Cms',
        'layoutManager' => 'im\cms\components\LayoutManager',
        'seo' => 'im\seo\components\Seo',
    ],
    'params' => $params,
];
