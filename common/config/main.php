<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => [
        'config' => '\im\config\Bootstrap',
        'cms' => 'im\cms\Bootstrap',
        'seo' => 'im\seo\Bootstrap',
        //'imshop' => 'im\imshop\Bootstrap',
        'pkbnt' => 'im\pkbnt\Bootstrap',
        'elasticsearc' => 'im\elasticsearch\Bootstrap',
        'tree' => 'im\tree\Bootstrap',
        'catalog' => 'im\catalog\Bootstrap',
        'search' => 'im\search\Bootstrap',
        'blog' => 'im\blog\Bootstrap',
        'users' => 'im\users\Bootstrap'
    ],
    'modules' => [
        'search' => 'im\search\Module',
    ],
    'components' => [
        'user' => [
            'class' => 'im\users\components\UserComponent',
            'identityClass' => 'im\users\models\User',
            //'enableAutoLogin' => true
            'loginUrl' => '/users/security/login'
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'contentCache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@frontend/runtime/cache'
        ],
        'memCache' => [
            'class' => 'yii\caching\MemCache',
        ],
        // Modules components
        'typesRegister' => 'im\base\types\EntityTypesRegister',
        'cms' => [
            'class' => 'im\cms\components\Cms',
            'cacheManager' => [
                'class' => 'im\cms\components\CacheManager',
                'caches' => [
                    'defaultCache' => 'contentCache'
                ]
            ]
        ],
        'layoutManager' => 'im\cms\components\LayoutManager',
        'templateManager' => 'im\cms\components\TemplateManager',
        'filesystem' => [
            'class' => 'im\filesystem\components\FilesystemComponent',
            'filesystems' => require(__DIR__ . '/filesystems.php')
        ],
        'searchManager' => 'im\search\components\SearchManager',
        'elasticsearch' => [
            'class' => 'yii\elasticsearch\Connection',
            'nodes' => [
                ['http_address' => '127.0.0.1:9200']
            ]
        ],
        'configManager' => 'im\config\components\ConfigManager',
        'config' => [
            'class' => 'im\config\components\Config',
            'configManager' => 'configManager'
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
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
    ],
];
