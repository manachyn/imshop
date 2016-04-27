<?php

namespace im\imshop;

use yii\web\AssetBundle;

class IEJqueryAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = null;

    /**
     * @inheritdoc
     */
    public $js = [
        '//code.jquery.com/jquery-1.11.3.min.js',
    ];

    /**
     * @inheritdoc
     */
    public $jsOptions = ['condition' => 'lt IE 9'];
}