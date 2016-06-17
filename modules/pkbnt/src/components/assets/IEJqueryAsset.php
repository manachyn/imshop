<?php

namespace im\pkbnt\components\assets;

use yii\web\AssetBundle;

/**
 * Class IEJqueryAsset
 * @package im\pkbnt\components\assets
 */
class IEJqueryAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = null;

    /**
     * @inheritdoc
     */
    public $js = [
        '//code.jquery.com/jquery-1.11.3.min.js',
    ];

    /**
     * @inheritdoc
     */
    public $jsOptions = ['condition' => 'lt IE 9'];
}
