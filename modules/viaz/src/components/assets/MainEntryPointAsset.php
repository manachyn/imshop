<?php

namespace im\viaz\components\assets;

use yii\web\AssetBundle;

/**
 * Class MainEntryPointAsset
 * @package im\viaz\components\assets
 * @author Ivan Manachyn <manachyn@gmail.com>
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