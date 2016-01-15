<?php
Yii::setAlias('@webroot', __DIR__ . '/../../web');
Yii::setAlias('@web', '/');

return [
    'entryPoints' => [
        'home' => [
            'class' => 'im\imshop\HomeEntryPointAsset',
            'depends' => [
                'im\imshop\ThemeAsset',
                'app\assets\AppAsset'
            ]
        ],
        'main' => [
            'class' => 'im\imshop\MainEntryPointAsset',
            'depends' => [
                'im\imshop\ThemeAsset'
            ]
        ]
    ],
    'assetManager' => [
        'basePath' => '@webroot/assets',
        'baseUrl' => '@web/assets',
    ]
];
//yii asset assets.php config/assets-prod.php
//php yii webpack/webpack assets/compression/webpack.php config/assets_compressed_webpack.php