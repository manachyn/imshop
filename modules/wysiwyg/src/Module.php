<?php

namespace im\wysiwyg;

use im\base\traits\ModuleTranslateTrait;
use Yii;

/**
 * Class Module
 * @package im\cms
 */
class Module extends \yii\base\Module
{
    use ModuleTranslateTrait;

    /**
     * @var string module messages category.
     */
    public static $messagesCategory = 'wysiwyg';
}
