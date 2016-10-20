<?php
Yii::setAlias('@webroot', Yii::getAlias('@frontend') . '/web');
Yii::setAlias('@web', '/');

return [
    'entryPoints' => [
        'home' => [
            'class' => 'im\imshop\components\assets\HomeEntryPointAsset',
            'depends' => [
                'im\imshop\components\assets\ThemeAsset',
                'im\imshop\components\assets\FrontendAsset'
            ]
        ],
        'main' => [
            'class' => 'im\imshop\components\assets\MainEntryPointAsset',
            'depends' => [
                'im\imshop\components\assets\ThemeAsset',
                'im\imshop\components\assets\FrontendAsset'
            ]
        ]
    ],
    'assetManager' => [
        'basePath' => '@webroot/assets',
        'baseUrl' => '@web/assets',
    ]
];