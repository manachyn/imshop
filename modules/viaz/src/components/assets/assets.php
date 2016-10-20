<?php

return [
//    'im\\viaz\\HomeAsset' => [
//        'class' => 'im\\viaz\\HomeAsset',
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
            'im\\viaz\\components\\assets\\HomeAsset',
        ],
    ],
    'yii\\bootstrap\\BootstrapAsset' => [
        'sourcePath' => null,
        'js' => [],
        'css' => [],
        'depends' => [
            'im\\viaz\\components\\assets\\HomeAsset',
        ],
    ],
    'yii\bootstrap\BootstrapPluginAsset' => [
        'sourcePath' => null,
        'js' => [],
        'css' => [],
        'depends' => [
            'im\\viaz\\components\\assets\\HomeAsset',
        ],
    ]
];