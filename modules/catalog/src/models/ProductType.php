<?php

namespace im\catalog\models;

use im\base\behaviors\RelationsBehavior;
use im\catalog\components\ProductTypeInterface;
use im\catalog\Module;
use im\eav\components\AttributeInterface;
use im\variation\components\OptionInterface;
use Yii;
use yii\db\ActiveRecord;

/**
 * Product type model class.
 *
 * @property int $id
 * @property string $name
 * @property ProductType $relatedParent
 * @property ProductAttribute[] $relatedEAttributes
 * @property ProductOption[] $relatedOptions
 */
class ProductType extends ActiveRecord implements ProductTypeInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%product_types}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            RelationsBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent_id'], 'integer'],
            [['eAttributes', 'options'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('product/productType', 'ID'),
            'name' => Module::t('product/productType', 'Name'),
            'parent_id' => Module::t('product/productType', 'Parent'),
            'eAttributes' => Module::t('product/productType', 'Attributes'),
            'options' => Module::t('product/productType', 'Options')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEAttributesRelation()
    {
        return $this->hasMany(ProductAttribute::className(), ['id' => 'attribute_id'])->viaTable('{{%product_type_attributes}}', ['product_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOptionsRelation()
    {
        return $this->hasMany(ProductOption::className(), ['id' => 'option_id'])->viaTable('{{%product_type_options}}', ['product_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentRelation()
    {
        return $this->hasOne(ProductType::className(), ['id' => 'parent_id'])->from(self::tableName() . ' AS parent');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildrenRelation()
    {
        return $this->hasMany(ProductType::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProductsRelation()
    {
        return $this->hasMany(Product::className(), ['type_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function getEAttributes()
    {
        $parent = $this->getParent();
        return $parent !== null ? array_merge($parent->getEAttributes(), $this->relatedEAttributes) : $this->relatedEAttributes;
        //return $this->relatedEAttributes;
    }

    /**
     * @inheritdoc
     */
    public function setEAttributes($attributes)
    {
        $this->relatedEAttributes = $attributes ?: null;
    }

    /**
     * @inheritdoc
     */
    public function addEAttribute(AttributeInterface $attribute)
    {
        if (!$this->hasAttribute($attribute)) {
            $this->relatedEAttributes = array_merge($this->relatedEAttributes, [$attribute]);
        }
    }

    /**
     * @inheritdoc
     */
    public function removeEAttribute(AttributeInterface $attribute)
    {
        if ($this->hasEAttribute($attribute)) {
            $attributes = $this->relatedEAttributes;
            foreach ($attributes as $key => $item) {
                if ($item->equals($attribute)) {
                    unset($attributes[$key]);
                }
            }
            $this->relatedEAttributes = $attributes;
        }
    }

    /**
     * @inheritdoc
     */
    public function hasEAttribute(AttributeInterface $attribute)
    {
        /** @var ProductAttribute $attribute */
        foreach ($this->relatedEAttributes as $item) {
            if ($item->equals($attribute)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        return $this->relatedOptions;
    }

    /**
     * @inheritdoc
     */
    public function setOptions($options)
    {
        $this->relatedOptions = $options;
    }

    /**
     * @inheritdoc
     */
    public function addOption(OptionInterface $option)
    {
        if (!$this->hasOption($option)) {
            $this->relatedOptions = array_merge($this->relatedOptions, [$option]);
        }
    }

    /**
     * @inheritdoc
     */
    public function removeOption(OptionInterface $option)
    {
        if ($this->hasOption($option)) {
            $options = $this->relatedOptions;
            foreach ($options as $key => $item) {
                if ($item->equals($option)) {
                    unset($options[$key]);
                }
            }
            $this->relatedEAttributes = $options;
        }
    }

    /**
     * @inheritdoc
     */
    public function hasOption(OptionInterface $option)
    {
        /** @var ProductOption $option */
        foreach ($this->relatedOptions as $item) {
            if ($item->equals($option)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function hasParent()
    {
        return $this->getParent() !== null;
    }

    /**
     * @inheritdoc
     */
    public function setParent(ProductTypeInterface $parent = null)
    {
        $this->relatedParent = $parent;
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
        return $this->relatedParent;
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return $this->name;
    }
}
