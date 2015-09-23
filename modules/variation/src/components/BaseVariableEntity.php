<?php

namespace im\variation\components;

use im\base\behaviors\RelationsBehavior;
use im\variation\models\Variant;
use yii\db\ActiveRecord;

/**
 * Base class for variable entities
 *
 * @property integer $id
 * @property Variant[] $relatedVariants
 */
abstract class BaseVariableEntity extends ActiveRecord implements VariableInterface
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => RelationsBehavior::className(),
                'settings' => ['relatedVariants' => ['deleteOnUnlink' => true]]
            ]
        ];
    }

    /**
     * @inheritdoc
     */
    abstract public function getEntityType();

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVariantsRelation()
    {
        return $this->hasMany(Variant::className(), ['entity_id' => 'id'])->where(['entity_type' => $this->getEntityType()]);
    }

    /**
     * @inheritdoc
     */
    public function getMasterVariant()
    {
        // TODO: Implement getMasterVariant() method.
    }

    /**
     * @inheritdoc
     */
    public function setMasterVariant(VariantInterface $variant)
    {
        // TODO: Implement setMasterVariant() method.
    }

    /**
     * @inheritdoc
     */
    public function hasVariants()
    {
        // TODO: Implement hasVariants() method.
    }

    /**
     * @inheritdoc
     */
    public function getVariants()
    {
        return $this->relatedVariants;
    }

    /**
     * @inheritdoc
     */
    public function setVariants($variants)
    {
        $this->relatedVariants = $variants;
        foreach ($variants as $variant) {
            /** @var Variant $variant */
            if ($variant->entity_id !== $this->id || $variant->entity_type !== $this->getEntityType()) {
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
            $this->relatedVariants = array_merge($this->relatedVariants, [$variant]);
            /** @var Variant $variant */
            if ($variant->entity_id !== $this->id || $variant->entity_type !== $this->getEntityType()) {
                $variant->setEntity($this);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function removeVariant(VariantInterface $variant)
    {
        if ($this->hasVariant($variant)) {
            $variants = $this->relatedVariants;
            /** @var Variant $variant */
            foreach ($variants as $key => $item) {
                if ($item->equals($variant)) {
                    unset($variants[$key]);
                }
            }
            $this->relatedVariants = $variants;
            $variant->setEntity(null);
        }
    }

    /**
     * @inheritdoc
     */
    public function hasVariant(VariantInterface $variant)
    {
        /** @var Variant $variant */
        foreach ($this->relatedVariants as $item) {
            if ($item->equals($variant)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function hasOptions()
    {
        // TODO: Implement hasOptions() method.
    }

    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        // TODO: Implement getOptions() method.
    }

    /**
     * @inheritdoc
     */
    public function setOptions($options)
    {
        // TODO: Implement setOptions() method.
    }

    /**
     * @inheritdoc
     */
    public function addOption(OptionInterface $option)
    {
        // TODO: Implement addOption() method.
    }

    /**
     * @inheritdoc
     */
    public function removeOption(OptionInterface $option)
    {
        // TODO: Implement removeOption() method.
    }

    /**
     * @inheritdoc
     */
    public function hasOption(OptionInterface $option)
    {
        // TODO: Implement hasOption() method.
    }
}