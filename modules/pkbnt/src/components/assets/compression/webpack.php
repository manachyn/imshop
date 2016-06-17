<?php
Yii::setAlias('@webroot', Yii::getAlias('@frontend') . '/web');
Yii::setAlias('@web', '/');

return [
    'entryPoints' => [
        'home' => [
            'class' => 'im\pkbnt\components\assets\HomeEntryPointAsset',
            'depends' => [
                'im\pkbnt\components\assets\ThemeAsset',
                'im\pkbnt\components\assets\FrontendAsset'
            ]
        ],
        'main' => [
            'class' => 'im\pkbnt\components\assets\MainEntryPointAsset',
            'depends' => [
                'im\pkbnt\components\assets\ThemeAsset',
                'im\pkbnt\components\assets\FrontendAsset'
            ]
        ]
    ],
    'assetManager' => [
        'basePath' => '@webroot/assets',
        'baseUrl' => '@web/assets',
    ]
];