<?php

namespace im\imshop\components\assets;

use yii\web\AssetBundle;

/**
 * Class ThemeAsset
 * @package im\imshop\components\assets
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
class ThemeAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@im/imshop/assets';

    /**
     * @inheritdoc
     */
    public $css = [
        'css/main.css'
    ];

    /**
     * @inheritdoc
     */
    public $js = [

    ];

    /**
     * @inheritdoc
     */
    public $publishOptions = [
        'only' => [
            'images/*',
            'images/*/*',
            'css/*.css',
            'js/*.js',
        ],
        'forceCopy' => YII_DEBUG
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'im\imshop\components\assets\FontAwesomeAsset'
    ];
}