<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'gii',
        'im\base\Bootstrap',
        'im\eav\Bootstrap',
        'im\variation\Bootstrap',
        'im\catalog\Bootstrap',
        'im\cms\Bootstrap',
        'im\elasticsearch\Bootstrap',
    ],
    'controllerNamespace' => 'app\commands',
    'modules' => [
        'gii' => 'yii\gii\Module',
        'search' => 'im\search\Module',
        'webpack' => 'im\webpack\Module'
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'typesRegister' => 'im\base\types\EntityTypesRegister',
        'searchManager' => 'im\search\components\SearchManager',
        'elasticsearch' => [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
                ['http_address' => '127.0.0.1:9200']
            ]
        ],
        'layoutManager' => 'im\cms\components\LayoutManager',
        'cms' => 'im\cms\components\Cms'
    ],
    'params' => $params,
];
