<?php

return [
//    'im\\imshop\\HomeAsset' => [
//        'class' => 'im\\imshop\\HomeAsset',
//        'basePath' => '@webroot/compiled-assets',
//        'baseUrl' => '@webroot/assets',
//        'js' => [
//            'compiled-assets/home.bundle.js',
//        ],
//        'css' => [
//            'compiled-assets/home.css',
//        ]
//    ],
    'yii\\web\\JqueryAsset' => [
        'sourcePath' => null,
        'js' => [],
        'css' => [],
        'depends' => [
            'im\\imshop\\HomeAsset',
        ],
    ],
    'yii\\bootstrap\\BootstrapAsset' => [
        'sourcePath' => null,
        'js' => [],
        'css' => [],
        'depends' => [
            'im\\imshop\\HomeAsset',
        ],
    ],
    'yii\bootstrap\BootstrapPluginAsset' => [
        'sourcePath' => null,
        'js' => [],
        'css' => [],
        'depends' => [
            'im\\imshop\\HomeAsset',
        ],
    ]
];