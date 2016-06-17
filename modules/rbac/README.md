```php
...
'modules' => [
    ...
    'rbac' => [
        'class' => 'im\rbac\Module',
    ],
    ...
],
...
```

```php
'components' => [
    ...
    'authManager' => [
        'class' => 'yii\rbac\DbManager',
    ],
    ...
],
...
```

```bash
$ php yii migrate/up --migrationPath=@yii/rbac/migrations
```

## Building Authorization Data

```bash
$ php yii rbac/rbac/init
```