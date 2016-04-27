<?php

namespace im\catalog\models;

/**
 * Product category file model class.
 */
class ProductCategoryFile extends CategoryFile
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_category_files}}';
    }
}