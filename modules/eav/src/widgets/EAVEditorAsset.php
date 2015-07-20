<?php

namespace im\eav\widgets;

use yii\web\AssetBundle;

class EAVEditorAsset extends AssetBundle
{
    public $sourcePath = '@im/eav/assets';

    public $js = [
        'js/eavEditor.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\widgets\PjaxAsset'
    ];

    public $publishOptions = [
        'forceCopy' => YII_DEBUG
    ];
}