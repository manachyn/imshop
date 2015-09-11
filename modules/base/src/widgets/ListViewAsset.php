<?php

namespace im\base\widgets;

use yii\web\AssetBundle;

class ListViewAsset extends AssetBundle
{
    public $sourcePath = '@im/base/assets';

    public $js = [
        'listView.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset',
        'yii\widgets\PjaxAsset'
    ];
}
