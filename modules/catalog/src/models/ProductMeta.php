<?php

namespace im\catalog\models;

use im\seo\models\Meta;

/**
 * Product meta model class.
 */
class ProductMeta extends Meta
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_meta}}';
    }

    /**
     * @inheritdoc
     */
    public function getEntity()
    {
        return $this->hasOne(Product::className(), ['id' => 'entity_id']);
    }
}
