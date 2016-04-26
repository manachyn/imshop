<?php

namespace im\tree\widgets;

use yii\web\AssetBundle;

class JsTreeToolbarAsset extends AssetBundle
{
    public $sourcePath = '@im/tree/assets';

    public $js = [
        'jsTreeToolbar.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
