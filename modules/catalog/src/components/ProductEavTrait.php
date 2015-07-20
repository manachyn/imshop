<?php

namespace im\catalog\components;

use im\catalog\models\ProductAttributeValue;
use im\eav\components\AttributeValueInterface;
use im\eav\components\EavTrait;
use yii\db\ActiveQuery;

/**
 * @property ProductAttributeValue[] $relatedEAttributes
 */
trait ProductEavTrait
{
    use EavTrait;

    /**
     * @return ActiveQuery
     */
    public function getEAttributesRelation()
    {
        return $this->hasMany(ProductAttributeValue::className(), ['entity_id' => 'id']);
    }

    /**
     * @param $config
     * @return AttributeValueInterface
     */
    public static function getAttributeValueInstance($config = [])
    {
        return ProductAttributeValue::getInstance($config);
    }

    /**
     * @inheritdoc
     */
    public function addEAttribute(AttributeValueInterface $attribute)
    {
        /** @var ProductAttributeValue $attribute */
        if (!$this->hasEAttribute($attribute)) {
            $this->relatedEAttributes = array_merge($this->relatedEAttributes, [$attribute]);
            if ($attribute->entity_id !== $this->id) {
                $attribute->setEntity($this);
            }
        }
    }


} 