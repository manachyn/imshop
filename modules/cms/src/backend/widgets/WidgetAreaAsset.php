<?php

namespace im\cms\backend\widgets;

use yii\web\AssetBundle;

class WidgetAreaAsset extends AssetBundle
{
    public $sourcePath = '@app/modules/cms/assets';

    public $js = [
        'widgetArea.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\widgets\PjaxAsset'
    ];
}
