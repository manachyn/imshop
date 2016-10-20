<?php

namespace im\viaz\components;

/**
 * Class Theme
 * @package im\viaz\components
 * @author Ivan Manachyn <manachyn@gmail.com>
 */
class Theme extends \yii\base\Theme
{
    /**
     * @inheritdoc
     */
    public $pathMap = [
        '@app/views' => '@im/viaz/views',
        '@app/modules' => '@im/viaz/modules',
        '@app/widgets' => '@im/viaz/widgets'
    ];
}
