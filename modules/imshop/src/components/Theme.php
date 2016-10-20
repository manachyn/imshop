<?php

namespace im\imshop\components;

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
        '@app/modules' => '@im/imshop/modules',
        '@app/widgets' => '@im/imshop/widgets'
    ];
}
