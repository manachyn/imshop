<?php

namespace im\catalog\models;

use im\seo\models\Meta;

/**
 * Product category meta model class.
 */
class ProductCategoryMeta extends Meta
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_category_meta}}';
    }

    /**
     * @inheritdoc
     */
    public function getEntity()
    {
        return $this->hasOne(ProductCategory::className(), ['id' => 'entity_id']);
    }
}
