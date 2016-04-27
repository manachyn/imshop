<?php

namespace im\elfinder;

use yii\web\AssetBundle;

class ElFinderAsset extends AssetBundle
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
        'yii\jui\JuiAsset'
    ];

    /**
     * @param \yii\web\View $view
     */
    public static function noConflict($view)
    {
        //list(, $url) = \Yii::$app->assetManager->publish('@im/elfinder/assets');
        list(, $url) = \Yii::$app->assetManager->publish('@app/modules/elfinder/src/assets');
        //$view->registerJsFile($url . '/js/no.conflict.js', ['depends' => [JqueryAsset::className()]]);
        $view->registerCssFile($url . '/css/no.conflict.css');
    }
} 