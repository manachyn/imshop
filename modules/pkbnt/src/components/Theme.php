<?php

namespace im\pkbnt\components;

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
//        '@app/modules' => '@im/pkbnt/modules',
//        '@app/widgets' => '@im/pkbnt/widgets',
        '@im/cms/frontend/views' => '@im/pkbnt/modules/cms/frontend/views',
        '@im/catalog/views' => '@im/pkbnt/modules/catalog/views'
    ];
}
