<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => [
        'cms' => 'im\cms\Bootstrap',
        'seo' => 'im\seo\Bootstrap',
        'imshop' => 'im\imshop\Bootstrap',
        'elasticsearc' => 'im\elasticsearch\Bootstrap',
        'tree' => 'im\tree\Bootstrap',
    ],
    'modules' => [
        'search' => 'im\search\Module',
    ],
    'components' => [
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
        ]
    ],
];
