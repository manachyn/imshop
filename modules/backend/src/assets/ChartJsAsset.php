<?php

namespace im\backend\assets;

use yii\web\AssetBundle;

/**
 * Class ChartJsAsset
 * @package im\backend\assets
 */
class ChartJsAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/almasaeed2010/adminlte/plugins/chartjs';

    /**
     * @inheritdoc
     */
    public $js = [
        'Chart.min.js'
    ];
}