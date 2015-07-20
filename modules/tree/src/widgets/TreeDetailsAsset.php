<?php

namespace im\tree\widgets;

use yii\web\AssetBundle;

class TreeDetailsAsset extends AssetBundle
{
    public $sourcePath = '@im/tree/assets';

    public $js = [
        'treeDetails.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
