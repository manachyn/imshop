<?php

namespace im\adminlte;

use yii\web\AssetBundle;

/**
 * Class IoniconsAsset
 * @package im\adminlte
 */
class IoniconsAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/driftyco/ionicons';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/ionicons.min.css',
        //'//code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css'
    ];
}