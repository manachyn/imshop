<?php

namespace im\adminlte;

use yii\web\AssetBundle;

class ThemeAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@im/adminlte/assets';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/custom.css'
    ];

    /**
     * @inheritdoc
     */
    public $js = [
        'js/admin.js'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'im\adminlte\AdminLteAsset'
    ];
}