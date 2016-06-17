<?php

namespace im\pkbnt\components;

use Yii;

/**
 * Class Theme
 * @package im\pkbnt
 */
class Theme extends \yii\base\Theme
{
    /**
     * @inheritdoc
     */
    public $pathMap = [
        '@app/views' => '@im/pkbnt/views',
        '@app/modules' => '@im/pkbnt/modules',
        '@app/widgets' => '@im/pkbnt/widgets'
    ];
}
