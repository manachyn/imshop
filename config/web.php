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
        'im\cms\Bootstrap',
    ],
    'modules' => [
        'base' => [
            'class' => 'im\base\Module'
        ],
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
        'search' => [
            'class' => 'im\search\backend\Module'
        ],
        'eav' => [
            'class' => 'im\eav\Module'
        ]
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
            'rules' => [
                ['pattern' => 'storage/<path:(.*)>', 'route' => 'glide/index', 'encodeParams' => false]
//                [
//                    'class' => 'im\base\routing\ModelUrlRule',
//                    'pattern' => '<url:.+>',
//                    'route' => 'site/index',
//                    'modelClass' => 'im\cms\models\Page'
//                ]
            ]
        ],


        'core' => [
            'class' => 'im\base\components\Core',
//            'entityTypes' => [
//                'page' => 'app\modules\cms\models\Page',
//                'product' => 'app\modules\catalog\models\Product'
//            ]
        ],
        'typesRegister' => [
            'class' => 'im\base\components\EntityTypesRegister',
            'entityTypes' => [
                'product' => 'im\catalog\models\Product'
            ]
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
        ],
        'search' => 'im\search\components\SearchManager',
        'layoutManager' => [
            'class' => 'im\cms\components\layout\LayoutManager',
            'layouts' => [
                [
                    'class' => 'im\cms\components\layout\Layout',
                    'id' => 'main',
                    'name' => 'Main layout',
                    'default' => true,
                    'availableWidgetAreas' => [
                        ['class' => 'im\cms\components\layout\WidgetAreaDescriptor', 'code' => 'sidebar', 'title' => 'Sidebar'],
                        ['class' => 'im\cms\components\layout\WidgetAreaDescriptor', 'code' => 'footer', 'title' => 'Footer']
                    ]
                ],
                [
                    'class' => 'im\cms\components\layout\Layout',
                    'id' => 'home',
                    'name' => 'Home',
                    'availableWidgetAreas' => [
                        ['class' => 'im\cms\components\layout\WidgetAreaDescriptor', 'code' => 'footer', 'title' => 'Footer']
                    ]
                ],
                [
                    'class' => 'im\cms\components\layout\Layout',
                    'id' => 'adaptive',
                    'name' => 'Adaptive layout',
                    'availableWidgetAreas' => [
                        ['class' => 'im\cms\components\layout\WidgetAreaDescriptor', 'code' => 'sidebar', 'title' => 'Sidebar'],
                        ['class' => 'im\cms\components\layout\WidgetAreaDescriptor', 'code' => 'footer', 'title' => 'Footer']
                    ]
                ]
            ]
        ],
        'templateManager' => [
            'class' => 'im\cms\components\TemplateManager'
        ],
        'glide' => [
            'class' => 'trntv\glide\components\Glide',
            'sourcePath' => '@app/web/files',
            'cachePath' => '@app/runtime/cache/glide',
            'urlManager' => 'urlManager',
            'maxImageSize' => 4000000,
            'signKey' => 'kmsTmQPdwm'
        ],
    ],
    'controllerMap' => [
        'glide' => '\trntv\glide\controllers\GlideController'
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
