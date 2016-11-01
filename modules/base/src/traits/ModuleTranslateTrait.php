<?php

namespace im\base\traits;

use Yii;

/**
 * Class ModuleTranslateTrait
 * @package im\base\traits
 */
trait ModuleTranslateTrait
{
    /**
     * Translate a message to the specified language using module translations.
     *
     * @param string $category the message category.
     * @param string $message the message to be translated.
     * @param array $params the parameters that will be used to replace the corresponding placeholders in the message.
     * @param string $language the language code.
     * @return string the translated message.
     * @see \Yii::t()
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        $category = static::$messagesCategory === $category ? $category : static::$messagesCategory . '/' . $category;

        return Yii::t($category, $message, $params, $language);
    }
}