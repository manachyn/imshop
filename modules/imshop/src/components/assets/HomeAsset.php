<?php

namespace im\imshop\components\assets;

use yii\web\AssetBundle;

/**
 * Class HomeAsset
 * @package im\imshop\components\assets
 * @author Ivan Manachyn <manachyn@gmail.com>
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