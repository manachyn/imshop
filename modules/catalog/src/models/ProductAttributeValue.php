<?php

namespace im\catalog\models;

use im\catalog\components\ProductAttributeValueInterface;
use im\catalog\components\ProductInterface;
use im\eav\components\AttributesHolderInterface;
use im\eav\models\AttributeValue;
use Yii;
use yii\db\ActiveQuery;

/**
 * Product attribute value class
 */
class ProductAttributeValue extends AttributeValue implements ProductAttributeValueInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_attribute_values}}';
    }

    /**
     * @return ActiveQuery
     */
    public function getEntityRelation()
    {
        return $this->hasOne(Product::className(), ['id' => 'entity_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function setEntity(AttributesHolderInterface $entity = null)
    {
        $this->relatedEntity = $entity;
        if ($entity !== null) {
            $entity->addEAttribute($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getProduct()
    {
        return parent::getEntity();
    }

    /**
     * {@inheritdoc}
     */
    public function setProduct(ProductInterface $product = null)
    {
        parent::setEntity($product);
    }
}
