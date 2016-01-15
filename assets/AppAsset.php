<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@app/assets';

    /**
     * @inheritdoc
     */
    public $js = ['js/app.js'];
}
