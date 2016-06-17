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
        'filesystem' => 'im\filesystem\Bootstrap',
        'catalog' => 'im\catalog\backend\Bootstrap',
        'eav' => 'im\eav\Bootstrap',
        'elfinder' => 'im\elfinder\Bootstrap',
        'wysiwyg' => 'im\wysiwyg\Bootstrap'
    ],
    'modules' => [
        'backend' => 'im\backend\Module',
        'cms' => 'im\cms\backend\Module',
        'blog' => 'im\blog\backend\Module',
        'catalog' => 'im\catalog\backend\Module',
        'eav' => 'im\eav\backend\Module',
        'filesystem' => 'im\filesystem\Module',
        'config' => 'im\config\Module',
        'rbac' => 'im\rbac\backend\Module',
        'users' => 'im\users\backend\Module'
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
            'rules' => require(__DIR__ . '/rules.php')
        ],
        // Modules components
        'backendTheme' => 'im\adminlte\Theme',
        'seo' => 'im\seo\components\Seo',
        'elFinder' => array_merge(require(__DIR__ . '/filemanager.php'), [
            'class' => '\im\elfinder\ElFinderComponent'
        ])
    ],
    'controllerMap' => [
        'elfinder' => [
            'class' => 'im\elfinder\ElFinderController',
            'enableCsrfValidation' => false,
        ]
    ],
    'params' => $params,
];
