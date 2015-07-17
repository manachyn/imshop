<?php

namespace im\base\widgets;

use yii\web\AssetBundle;

class ToolbarAsset extends AssetBundle
{

    public $sourcePath = '@app/modules/base/assets';

    public $js = [
        'im.toolbar.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
