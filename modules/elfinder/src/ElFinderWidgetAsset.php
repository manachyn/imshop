<?php

namespace im\elfinder;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

class ElFinderWidgetAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@im/elfinder/assets';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/elfinder.min.css',
        'css/theme.css'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/elfinder.min.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
        'yii\jui\JuiAsset',
        'im\elfinder\JqueryMigrateAsset'
    ];

    /**
     * @param \yii\web\View $view
     */
    public static function noConflict($view)
    {
        list(, $url) = \Yii::$app->assetManager->publish('@im/elfinder/assets');
        //$view->registerJsFile($url . '/js/no.conflict.js', ['depends' => [JqueryAsset::className()]]);
        $view->registerCssFile($url . '/css/no.conflict.css');
    }
} 