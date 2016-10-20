<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'eav' => 'im\eav\Bootstrap',
        'variation' => 'im\variation\Bootstrap',
    ],
    'controllerNamespace' => 'console\controllers',
    'modules' => [
        'webpack' => 'im\webpack\Module',
        'rbac' => [
            'class' => 'im\rbac\Module',
            'authDataProviders' => [
                'im\rbac\Bootstrap',
                'im\users\Bootstrap'
            ]
        ],
        'pkbnt' => 'im\pkbnt\Module',
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'params' => $params,
];
