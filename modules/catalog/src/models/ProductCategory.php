<?php

namespace im\catalog\models;

use creocoder\nestedsets\NestedSetsBehavior;
use yii\behaviors\SluggableBehavior;

/**
 * Product category model class.
 *
 * @property Product[] $products
 */
class ProductCategory extends Category
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_categories}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return array_merge(parent::behaviors(), [
            'sluggable' => [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'ensureUnique' => true
            ],
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => false
            ]
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProducts()
    {
        return $this->hasMany(Product::className(), ['id' => 'product_id'])->viaTable('{{%products_categories}}', ['category_id' => 'id']);
    }
}
