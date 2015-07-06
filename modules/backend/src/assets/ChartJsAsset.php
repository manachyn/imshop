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
    public $sourcePath = '@bower/chartjs';

    /**
     * @inheritdoc
     */
    public $js = [
        'Chart.min.js'
    ];
}