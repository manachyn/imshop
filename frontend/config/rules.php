<?php

return [
    ['pattern' => '/', 'route' => 'cms/page/view', 'defaults' => ['path' => 'index']],
    ['pattern' => 'storage/<server:\w+>/<path:(.*)>', 'route' => 'glide/index', 'encodeParams' => false],
    [
        'class' => 'im\base\routing\GroupUrlRule',
        'pattern' => '<path:[a-zA-Z0-9_\-.]+>/<query:.+>',
        'defaults' => ['query' => ''],
        'encodeParams' => false,
        'resolvers' => [
            [
                'class' => 'im\base\routing\ModelRouteResolver',
                'route' => 'catalog/product-category/view',
                'modelClass' => 'im\catalog\models\ProductCategory'
            ],
//            [
//                'class' => 'im\base\routing\ModelRouteResolver',
//                'route' => 'search/search-page/view',
//                'modelClass' => 'im\search\models\SearchPage'
//            ]
        ]
    ],
    [
        'class' => 'im\base\routing\GroupUrlRule',
        'pattern' => '<path:.+>',
        'defaults' => ['path' => 'index'],
        'encodeParams' => false,
        'resolvers' => [
            [
                'class' => 'im\base\routing\ModelRouteResolver',
                'route' => 'catalog/product/view',
                'modelClass' => 'im\catalog\models\Product'
            ],
            [
                'class' => 'im\cms\components\PageModelRouteResolver',
                'route' => 'cms/page/view',
                'childRoute' => 'cms/page/child',
                'modelClass' => 'im\cms\models\Page',
            ],
        ]
    ]
];