<?php

namespace im\imshop\components\assets;

use yii\web\AssetBundle;

/**
 * Class FrontendAsset
 * @package im\imshop\components\assets
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
class FrontendAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@im/imshop/assets';

    /**
     * @inheritdoc
     */
    public $js = ['js/app.js'];
}
