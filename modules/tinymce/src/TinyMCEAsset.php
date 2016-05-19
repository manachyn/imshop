<?php

namespace im\tinymce;

use yii\web\AssetBundle;

/**
 * Class TinyMCEAsset
 * @package im\tinymce
 */
class TinyMCEAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = __DIR__ . '/assets/tinymce';

    /**
     * @inheritdoc
     */
    public $js = [
        'jquery.tinymce.min.js',
        'tinymce.min.js'
    ];

    /**
     * @inheritdoc
     */
    public $css = [
        'skins/lightgray/skin.min.css'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\bootstrap\BootstrapAsset'
    ];
}

