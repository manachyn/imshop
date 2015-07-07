Yii2 control widget.
==================

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist vova07/yii2-control-widget "*"
```

or add

```
"vova07/yii2-control-widget": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
echo \vova07\control\Widget::widget([
    'title' => 'Create new record',
    'items' => [
        'cancel' => [
            'visible' => true
        ]
    ]
]);
```