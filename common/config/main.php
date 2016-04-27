<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'bootstrap' => [
        'cms' => 'im\cms\Bootstrap',
        'seo' => 'im\seo\Bootstrap',
        'imshop' => 'im\imshop\Bootstrap',
        'elasticsearc' => 'im\elasticsearch\Bootstrap',
    ],
    'modules' => [
        'search' => 'im\search\Module',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'memCache' => [
            'class' => 'yii\caching\MemCache',
        ],
        // Modules components
        'typesRegister' => 'im\base\types\EntityTypesRegister',
        'cms' => 'im\cms\components\Cms',
        'layoutManager' => 'im\cms\components\LayoutManager',
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
    ],
];
