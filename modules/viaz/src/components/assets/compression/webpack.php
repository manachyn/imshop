<?php
Yii::setAlias('@webroot', Yii::getAlias('@frontend') . '/web');
Yii::setAlias('@web', '/');

return [
    'entryPoints' => [
        'home' => [
            'class' => 'im\viaz\components\assets\HomeEntryPointAsset',
            'depends' => [
                'im\viaz\components\assets\ThemeAsset',
                'im\viaz\components\assets\FrontendAsset'
            ]
        ],
        'main' => [
            'class' => 'im\viaz\components\assets\MainEntryPointAsset',
            'depends' => [
                'im\viaz\components\assets\ThemeAsset',
                'im\viaz\components\assets\FrontendAsset'
            ]
        ]
    ],
    'assetManager' => [
        'basePath' => '@webroot/assets',
        'baseUrl' => '@web/assets',
    ]
];