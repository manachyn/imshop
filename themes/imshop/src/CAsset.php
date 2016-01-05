<?php

namespace im\imshop;

use yii\web\AssetBundle;


class CAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@im/imshop/assets';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/c.js'
    ];
}