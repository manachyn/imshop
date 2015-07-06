<?php

namespace im\backend\assets;

use yii\web\AssetBundle;

/**
 * Class DashboardPluginsAsset
 * @package im\backend\assets
 */
class DashboardPluginsAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/almasaeed2010/adminlte/plugins';

    /**
     * @inheritdoc
     */
    public $css = [
        'jvectormap/jquery-jvectormap-1.2.2.css'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'sparkline/jquery.sparkline.min.js',
        'jvectormap/jquery-jvectormap-1.2.2.min.js',
        'jvectormap/jquery-jvectormap-world-mill-en.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}