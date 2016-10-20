<?php

namespace im\viaz\components\assets;

use yii\web\AssetBundle;

/**
 * Class FrontendAsset
 * @package im\viaz\components\assets
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
class FrontendAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@im/viaz/assets';

    /**
     * @inheritdoc
     */
    public $js = ['js/app.js'];
}
