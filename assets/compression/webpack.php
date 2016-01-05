<?php
return [
//    'bundles' => [
//         'yii\web\YiiAsset',
//         'im\imshop\AAsset',
//         'im\imshop\BAsset'
//    ],
    'entryPoints' => [
        'pageA' => [
            'class' => 'app\assets\AllAsset',
            'js' => 'pageA.js',
            'basePath' => realpath(__DIR__ . '/../../web/assets'),
            'baseUrl' => '/',
            'depends' => [
                'yii\web\YiiAsset',
                'im\imshop\AAsset'
            ],
        ],
        'pageB' => [
            'class' => 'yii\web\AssetBundle',
            'js' => 'pageB.js',
            'basePath' => realpath(__DIR__ . '/../../web/assets'),
            'baseUrl' => '/',
            'depends' => [
                'yii\web\YiiAsset',
                'im\imshop\BAsset'
            ],
        ],
    ],
    // Asset manager configuration:
    'assetManager' => [
        //'basePath' => '@webroot/assets',
        //'baseUrl' => '@web/assets',
        'basePath' => realpath(__DIR__ . '/../../web/assets'),
        'baseUrl'  => '/assets',
    ],
];

//yii asset assets.php config/assets-prod.php
//php yii webpack/webpack assets/compression/webpack.php config/assets_compressed_webpack.php