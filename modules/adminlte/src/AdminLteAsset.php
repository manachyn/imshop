<?php

namespace im\adminlte;

use yii\web\AssetBundle;

/**
 * Class AdminLteAsset
 * @package im\adminlte
 */
class AdminLteAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/almasaeed2010/adminlte/dist';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/AdminLTE.min.css',
        'css/skins/_all-skins.min.css'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/app.min.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'im\adminlte\FontAwesomeAsset',
        'im\adminlte\IoniconsAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];
}