<?php

namespace im\viaz\components\assets;

use yii\web\AssetBundle;

/**
 * Class ThemeAsset
 * @package im\viaz\components\assets
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
class ThemeAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@im/viaz/assets';

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
        'im\viaz\components\assets\FontAwesomeAsset'
    ];
}