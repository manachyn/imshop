<?php

namespace im\pkbnt\components\assets;

use yii\web\AssetBundle;

/**
 * Class MainAsset
 * @package im\viaz\components\assets
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
class MainAsset extends AssetBundle
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
    public $js = ['compiled-assets/main.bundle.js'];

    /**
     * @inheritdoc
     */
    public $css = ['compiled-assets/main.css'];
}