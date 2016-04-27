<?php

namespace im\backend\assets;

use yii\web\AssetBundle;

/**
 * Class DashboardAsset
 * @package im\backend\assets
 */
class DashboardAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/almasaeed2010/adminlte/dist';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/pages/dashboard2.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'im\backend\assets\DashboardPluginsAsset',
        'im\backend\assets\ChartJsAsset'
    ];
}