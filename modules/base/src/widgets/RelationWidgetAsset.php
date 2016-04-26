<?php

namespace im\base\widgets;

use yii\web\AssetBundle;

class RelationWidgetAsset extends AssetBundle
{
    public $sourcePath = '@im/base/assets';

    public $js = [
        'relationWidget.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
        'yii\widgets\PjaxAsset'
    ];
}
