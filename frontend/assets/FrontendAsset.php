<?php

namespace frontend\assets;

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
    public $sourcePath = '@frontend/assets';

    /**
     * @inheritdoc
     */
    public $js = ['js/app.js'];
}
