<?php

namespace im\catalog\models;

use im\variation\models\OptionValue;

/**
 * Product option value class
 */
class ProductOptionValue extends OptionValue
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_option_values}}';
    }
}
