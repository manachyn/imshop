<?php

namespace im\catalog\components;

use im\catalog\models\ProductVariant;
use im\variation\components\VariableEntityTrait;
use im\variation\components\VariantInterface;
use im\variation\models\Variant;

/**
 * @property integer $id
 * @property Variant[] $relatedVariants
 */
trait VariableProductTrait
{
    use VariableEntityTrait;

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVariantsRelation()
    {
        return $this->hasMany(ProductVariant::className(), ['entity_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function setVariants($variants)
    {
        $this->relatedVariants = $variants;
        foreach ($variants as $variant) {
            /** @var Variant $variant */
            if ($variant->entity_id !== $this->id) {
                $variant->setEntity($this);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function addVariant(VariantInterface $variant)
    {
        if (!$this->hasVariant($variant)) {
            /** @var Variant $variant */
            $this->relatedVariants = array_merge($this->relatedVariants, [$variant]);
            if ($variant->entity_id !== $this->id) {
                $variant->setEntity($this);
            }
        }
    }
} 