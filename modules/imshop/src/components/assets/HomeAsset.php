<?php

namespace im\imshop;

use Yii;
use yii\web\AssetBundle;

class HomeAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $basePath = '@webroot/compiled-assets';

    /**
     * @inheritdoc
     */
    public $baseUrl = YII_ENV_DEV ? 'http://localhost:8080/' : '/';

    /**
     * @inheritdoc
     */
    public $js = ['compiled-assets/home.bundle.js'];

    /**
     * @inheritdoc
     */
    public $css = ['compiled-assets/home.css'];
}