<?php

namespace im\imshop;

use yii\web\AssetBundle;


class BAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@im/imshop/assets';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/b.css'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/b.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'im\imshop\CAsset'
    ];
}