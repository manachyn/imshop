<?php

namespace im\catalog\models;

use im\variation\models\Variant;
use Yii;

/**
 * This is the model class for table "{{%product_variants}}".
 *
 * @property string $sku
 * @property string $price
 */
class ProductVariant extends Variant
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_variants}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['sku', 'price'], 'safe']
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'entity_id' => Yii::t('app', 'Product'),
            'sku' => Yii::t('app', 'Sku'),
            'price' => Yii::t('app', 'Price')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getEntityRelation()
    {
        return $this->hasOne(Product::className(), ['id' => 'entity_id']);
    }
}
