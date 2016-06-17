CONFIGURATION
-------------

### TemplateManager component

Add component to the file `config/web.php`, for example:

```php
'templateManager' => [
    'class' => 'im\cms\components\TemplateManager'
],
```

### LayoutManager component

```php
'layoutManager' => [
    'class' => 'im\cms\components\layout\LayoutManager',
    'layouts' => [
        [
            'class' => 'im\cms\components\layout\Layout',
            'id' => 'main',
            'name' => 'Main layout',
            'default' => true,
            'availableWidgetAreas' => [
                ['class' => 'im\cms\components\layout\WidgetAreaDescriptor', 'code' => 'sidebar', 'title' => 'Sidebar'],
                ['class' => 'im\cms\components\layout\WidgetAreaDescriptor', 'code' => 'footer', 'title' => 'Footer']
            ]
        ],
        [
            'class' => 'im\cms\components\layout\Layout',
            'id' => 'home',
            'name' => 'Home'
        ]
    ]
]
```

Register layout from extension

```php
$layoutManager = $app->get('layoutManager');
$layoutManager->registerLayout(new Layout());
```

Register widget from extension

```php
$layoutManager = $app->get('layoutManager');
$layoutManager->registerWidgetClass('im\cms\models\ContentWidget');
```


'typesRegister' => 'im\base\types\EntityTypesRegister',
'layoutManager' => 'im\cms\components\LayoutManager',


php yii migrate --migrationPath=@im/cms/migrations