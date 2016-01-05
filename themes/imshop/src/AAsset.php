<?php

namespace im\imshop;

use yii\web\AssetBundle;


class AAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@im/imshop/assets';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/a.css'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/a.js'
    ];
}