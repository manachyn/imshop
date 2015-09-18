<?php

namespace im\imshop;

use Yii;

/**
 * IMShop theme.
 *
 * @package im\imshop
 */
class Theme extends \yii\base\Theme
{
    /**
     * @inheritdoc
     */
    public $pathMap = [
        '@app/views' => '@im/imshop/views',
        '@app/modules' => '@im/imshop/modules'
    ];
}
