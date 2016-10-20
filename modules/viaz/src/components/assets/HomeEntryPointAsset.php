<?php

namespace im\viaz\components\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * Class HomeEntryPointAsset
 * @package im\viaz\components\assets
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
class HomeEntryPointAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $basePath = '@webroot/assets';

//    /**
//     * @inheritdoc
//     */
//    public $baseUrl = '/';

    /**
     * @inheritdoc
     */
    public $js = 'home.js';

    /**
     * @return AssetBundle
     */
    public static function getBundleAsset()
    {
        return Yii::createObject([
            'class' => HomeEntryPointAsset::className(),
            'basePath' => '@webroot/compiled-assets',
            'baseUrl' => '/',
            'js' => [
                'compiled-assets/home.bundle.js',
            ],
            'css' => [
                'compiled-assets/home.css',
            ]
        ]);
    }
}