<?php

namespace im\imshop;

use yii\web\AssetBundle;

class ThemeAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@im/imshop/assets';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/main.css'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/a.js'
    ];

    /**
     * @inheritdoc
     */
    public $publishOptions = [
        'only' => [
            'images/*',
            'images/*/*',
            'css/*.css',
            'js/*.js',
        ],
        'forceCopy' => YII_DEBUG
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'im\imshop\FontAwesomeAsset',
        'im\imshop\BAsset'
    ];
}