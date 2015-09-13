<?php

namespace im\catalog\models;

use im\seo\models\Meta;

/**
 * Product category meta model class.
 */
class ProductCategoryMeta extends Meta
{
    const ENTITY_TYPE = 'product_category_meta';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_category_meta}}';
    }
}
