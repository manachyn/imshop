<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log',
        'im\base\Bootstrap',
        'im\users\Bootstrap',
        'im\eav\Bootstrap',
        'im\seo\Bootstrap',
        'im\filesystem\Bootstrap',
        'im\catalog\Bootstrap',
    ],
    'modules' => [
        'users' => [
            'class' => 'im\users\Module'
        ],
        'cms' => [
            'class' => 'im\cms\Module'
        ],
        'catalog' => [
            'class' => 'im\catalog\Module'
        ],
        'filesystem' => [
            'class' => 'im\filesystem\Module'
        ],
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'zDPCtYx9C1ULvuuhUi7myprETRR_FFn-',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class' => 'im\users\components\UserComponent',
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
//            'useFileTransport' => true,
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
        'db' => require(__DIR__ . '/db.php'),

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'cache' => false,
//            'rules' => [
//                [
//                    'class' => 'im\base\routing\ModelUrlRule',
//                    'pattern' => '<url:.+>',
//                    'route' => 'site/index',
//                    'modelClass' => 'im\cms\models\Page'
//                ]
//            ]
        ],


        'core' => [
            'class' => 'im\base\components\Core',
//            'entityTypes' => [
//                'page' => 'app\modules\cms\models\Page',
//                'product' => 'app\modules\catalog\models\Product'
//            ]
        ],
        'filesystem' => [
            'class' => 'im\filesystem\components\FilesystemComponent',
            'filesystems' => require(__DIR__ . '/filesystems.php')
        ],
        'seo' => [
            'class' => 'im\seo\components\Seo',
            'seoModels' => [
                'im\catalog\models\Category',
                'im\catalog\models\Product'
            ],
//            'metaTypes' => [
//                'page_meta' => 'app\modules\seo\models\PageMeta',
//                'category_meta' => 'app\modules\catalog\models\ProductMeta',
//                'product_meta' => 'app\modules\catalog\models\ProductMeta'
//            ],
            'metaTypeSocialMetaTypes' => [
//                'product_meta' => ['open_graph']
            ]
        ],
        'backendTheme' => 'im\adminlte\Theme',
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    'class' => 'im\users\clients\Facebook',
                    'clientId' => '507449656079588',
                    'clientSecret' => 'd82712a6066ba2310eb6c20e770c28e2'
                ],
                'google' => [
                    'class' => 'im\users\clients\Google',
                    'clientId' => '855363173801-ub3r8uvbkc5458anquemm8jcuvogfvug.apps.googleusercontent.com',
                    'clientSecret' => 'oO1HUqyxUpBpaxM1gWQ4ALIL'
                ],
//                'vkontakte' => [
//                    'class' => 'yii\authclient\clients\VKontakte',
//                    'clientId' => 'vkontakte_client_id',
//                    'clientSecret' => 'vkontakte_client_secret',
//                ]
            ],
        ]
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
