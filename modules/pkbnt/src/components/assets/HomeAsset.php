<?php

namespace im\pkbnt\components\assets;

use Yii;
use yii\web\AssetBundle;

/**
 * Class HomeAsset
 * @package im\pkbnt\components\assets
 */
class HomeAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $basePath = '@webroot/compiled-assets';

    /**
     * @inheritdoc
     */
    //public $baseUrl = YII_ENV_DEV ? 'http://localhost:8080/' : '/';
    public $baseUrl = '/';

    /**
     * @inheritdoc
     */
    public $js = ['compiled-assets/home.bundle.js'];

    /**
     * @inheritdoc
     */
    public $css = ['compiled-assets/home.css'];
}