<?php

namespace im\pkbnt\components\assets;

use yii\web\AssetBundle;

/**
 * Class ThemeAsset
 * @package im\pkbnt\components\assets
 */
class ThemeAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@im/pkbnt/assets';

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
        'im\pkbnt\components\assets\FontAwesomeAsset'
    ];
}
