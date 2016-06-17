<?php

namespace im\pkbnt\components\assets;

use yii\web\AssetBundle;

/**
 * Class MainEntryPointAsset
 * @package im\pkbnt\components\assets
 */
class MainEntryPointAsset extends AssetBundle
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
    public $js = 'main.js';
}