<?php

namespace im\pkbnt\components\assets;

use yii\web\AssetBundle;

/**
 * Class FrontendAsset
 * @package app\assets
 */
class FrontendAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@im/pkbnt/assets';

    /**
     * @inheritdoc
     */
    public $js = ['js/app.js'];
}
