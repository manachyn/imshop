<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'language' => 'ru',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
        'log' => 'log',
        'base' => 'im\base\Bootstrap',
        'cms' => 'im\cms\frontend\Bootstrap',
        'seo' => 'im\seo\frontend\Bootstrap',
        'search' => 'im\search\frontend\Bootstrap',
        'form' => 'im\forms\Bootstrap',
        //'elasticsearch' => 'im\elasticsearch\Bootstrap',
    ],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'cms' => 'im\cms\frontend\Module',
        'catalog' => 'im\catalog\Module',
        'blog' => 'im\blog\frontend\Module',
        'search' => 'im\search\frontend\Module',
        'users' => 'im\users\Module'
    ],
    'components' => [
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'request' => [
            'baseUrl' => '',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //'cache' => 'memCache',
            'rules' => require(__DIR__ . '/rules.php')
        ],
        'view' => [
            'theme' => ['class' => 'im\pkbnt\components\Theme']
            //'theme' => ['class' => 'im\imshop\components\Theme']
            //'theme' => ['class' => 'im\viaz\components\Theme']
        ],
        'assetManager' => [
            'bundles' => require(__DIR__ . '/../../vendor/imsoft/pkbnt/src/components/assets/assets.php'),
            //'bundles' => require(__DIR__ . '/../../vendor/imsoft/imshop/src/components/assets/assets.php'),
            //'bundles' => require(__DIR__ . '/../../vendor/imsoft/viaz/src/components/assets/assets.php'),
        ],
        'seo' => 'im\seo\components\Seo',
        'categorySearch' => 'im\catalog\components\search\CategorySearchComponent',
        'pageFinder' => 'im\cms\components\PageFinder',
        'shortcodes' => 'im\shortcodes\Shortcode'
    ],
    'params' => $params,
];
