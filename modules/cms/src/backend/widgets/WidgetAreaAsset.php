<?php

namespace im\base\widgets;

use yii\web\AssetBundle;

class WidgetAreaAsset extends AssetBundle
{
    public $sourcePath = '@im/cms/assets';

    public $js = [
        'widgetArea.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\widgets\PjaxAsset'
    ];
}
