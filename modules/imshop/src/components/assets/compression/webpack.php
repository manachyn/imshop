<?php
Yii::setAlias('@webroot', dirname(dirname(__DIR__)) . '/web');
Yii::setAlias('@web', '/');

return [
    'entryPoints' => [
        'home' => [
            'class' => 'im\imshop\HomeEntryPointAsset',
            'depends' => [
                'im\imshop\ThemeAsset',
                'frontend\assets\FrontendAsset'
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