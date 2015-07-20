<?php

namespace im\tree\widgets;

use yii\web\AssetBundle;

class JsTreeApiAsset extends AssetBundle
{
    public $sourcePath = '@im/tree/assets';

    public $js = [
        'jsTreeApi.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}