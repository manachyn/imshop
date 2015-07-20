<?php

namespace im\eav\components;

use im\base\interfaces\TypeableEntityInterface;
use im\eav\models\Attribute;
use im\eav\models\AttributeValue;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * @property integer $id
 * @property AttributeValue[] $relatedEAttributes
 */
trait EavTrait
{
    private $_eAttributeLabels = [];

    /**
     * @return ActiveQuery
     */
    public function getEAttributesRelation()
    {
        return $this->hasMany(AttributeValue::className(), ['entity_id' => 'id'])/*->with('relatedEntityAttribute')*/->where(['entity_type' => $this->getEntityType()]);
    }

    /**
     * @param $name
     * @return AttributeInterface
     */
    public static function getAttributeInstanceByName($name)
    {
        return Attribute::getInstanceByName($name);
    }

    /**
     * @param $config
     * @return AttributeValueInterface
     */
    public static function getAttributeValueInstance($config = [])
    {
        return AttributeValue::getInstance($config);
    }

    /**
     * @inheritdoc
     */
    public function getEAttributes()
    {
        return $this->groupEAttributesBy($this->relatedEAttributes, 'attribute_name');
    }

    /**
     * @inheritdoc
     */
    public function setEAttributes($attributes)
    {
        $attributes = $this->normalizeEAttributes($attributes);
        $this->relatedEAttributes = $attributes;
        foreach ($attributes as $attribute) {
            if ($attribute instanceof AttributeValueInterface) {
                $attribute->setEntity($this);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function setEAttribute($name, $value)
    {
        $attribute = $this->normalizeEAttribute($value, $name);
        $this->removeEAttributeByName($name);
        $this->setEAttributes(array_merge($this->relatedEAttributes, (array) $attribute));
    }

    /**
     * @inheritdoc
     */
    public function addEAttribute(AttributeValueInterface $attribute)
    {
        /** @var AttributeValue $attribute */
        if (!$this->hasEAttribute($attribute)) {
            $this->relatedEAttributes = array_merge($this->relatedEAttributes, [$attribute]);
            if ($attribute->entity_id !== $this->id || $attribute->entity_type !== $this->getEntityType()) {
                $attribute->setEntity($this);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function removeEAttribute(AttributeValueInterface $attribute)
    {
        if ($this->hasEAttribute($attribute)) {
            $attributes = $this->relatedEAttributes;
            foreach ($attributes as $key => $item) {
                if ($item->equals($attribute)) {
                    unset($attributes[$key]);
                }
            }
            $this->relatedEAttributes = $attributes;
            $attribute->setEntity(null);
        }
    }

    /**
     * @inheritdoc
     */
    public function removeEAttributeByName($name)
    {
        if ($this->hasEAttributeByName($name)) {
            $attributes = $this->relatedEAttributes;
            foreach ($attributes as $key => $item) {
                if ($item->getName() === $name) {
                    unset($attributes[$key]);
                    $item->setEntity(null);
                }
            }
            $this->relatedEAttributes = $attributes;
        }
    }

    /**
     * @inheritdoc
     */
    public function hasEAttribute(AttributeValueInterface $attribute)
    {
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
    public function hasEAttributeByName($name)
    {
        foreach ($this->relatedEAttributes as $item) {
            if ($item->getName() === $name) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function getEAttribute($name)
    {
        foreach ($this->getEAttributes() as $attribute) {
            if ($attribute->getName() === $name) {
                return $attribute;
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        try { parent::__set($name, $value); }
        catch (\Exception $e) {
            if ($this->isEAttribute($name)) {
                $name = $this->normalizeEAttributeName($name);
                $this->setEAttribute($name, $value);
            } else {
                throw $e;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        try { return parent::__get($name); }
        catch (\Exception $e) {
            if ($this->isEAttribute($name)) {
                $name = $this->normalizeEAttributeName($name);
                return ($attribute = $this->getEAttribute($name)) !== null ? $attribute->getValue() : null;
            } else {
                throw $e;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getAttributeLabel($attribute)
    {
        $labels = $this->attributeLabels();
        if (isset($labels[$attribute])) {
            return $labels[$attribute];
        } elseif ($this->isEAttribute($attribute)) {
            $attribute = $this->normalizeEAttributeName($attribute);
            if (isset($this->_eAttributeLabels[$attribute])) {
                return $this->_eAttributeLabels[$attribute];
            }
            /** @var AttributeInterface $attribute */
            $model = Attribute::findOne(['name' => $attribute]);
            if ($model !== null) {
                return $this->_eAttributeLabels[$attribute] = $model->getPresentation();
            } else {
                return $this->generateAttributeLabel($attribute);
            }
        } else {
            return $this->generateAttributeLabel($attribute);
        }
    }

    /**
     * Populates the model attributes with the data.
     * @param array $data the data array.
     * @return boolean whether the model attributes is successfully populated with some data.
     */
    public function loadEAttributes($data)
    {
        /** @var AttributeValue $model */
        $model = static::getAttributeValueInstance();
        $scope = $model->formName();
        if (isset($data[$scope])) {
            $this->setEAttributes($data[$scope]);
        }

        return true;
    }

    /**
     * @return string
     */
    private function getEntityType() {
        return $this instanceof TypeableEntityInterface ? $this->getEntityType() : get_class($this);
    }

    private function isEAttribute($name)
    {
        return strncmp($name, 'eAttributes.', 12) === 0;
    }

    private function normalizeEAttributeName($name) {
        if (strncmp($name, 'eAttributes.', 12) === 0) {
            $name = substr($name, 12);
        }

        return $name;
    }

    /**
     * @param AttributeValueInterface[] $attributes
     * @param string $column
     * @return AttributeValueInterface[]
     */
    protected function groupEAttributesBy($attributes, $column)
    {
        /** @var AttributeValue[] $grouped */
        $grouped = [];
        foreach ($attributes as $attribute) {
            if (isset($grouped[$attribute->{$column}])) {
                $grouped[$attribute->{$column}] = clone $grouped[$attribute->{$column}];
                if (!in_array($grouped[$attribute->{$column}]->getType(), AttributeTypes::getTypes())) {
                    $v = $grouped[$attribute->{$column}]->getValue();
                    $grouped[$attribute->{$column}]->setValue($v);
                }
                $v1 = $grouped[$attribute->{$column}]->getValue();
                $v2 = $attribute->getValue();
                $v1 = is_array($v1) ? $v1 : [$v1];
                $v2 = is_array($v2) ? $v2 : [$v2];
                $value = array_merge($v1, $v2);
                if (!in_array($grouped[$attribute->{$column}]->getType(), AttributeTypes::getTypes())) {
                    $grouped[$attribute->{$column}]->populateRelation('relatedValueEntity', $value);
                } else {
                    $grouped[$attribute->{$column}]->string_value = $value;
                }
            }
            else {
                $grouped[$attribute->{$column}] = $attribute;
            }
        }

        return $grouped;
    }

    protected function normalizeEAttributes($attributes)
    {
        $normalized = [];
        foreach ($attributes as $key => $value) {
            $value = $this->normalizeEAttributeValue($value);
            $normalized = array_merge($normalized, (array) $this->normalizeEAttribute($value, $key));
        }
        return $normalized;
    }

    protected function normalizeEAttribute($attribute, $key)
    {
        $valuePrototype = null;
        $attributePrototype = null;
        $oldValues = is_string($key) ? $this->getEAttributeOldValues($key) : [];
        if (is_array($attribute) && !empty($valuePrototype) && $valuePrototype->getType() == AttributeTypes::SERIALIZED_TYPE) {
            $valuePrototype->setValue($attribute);
            return $valuePrototype;
        } else {
            if (!is_array($attribute)) {
                $attribute = [$attribute];
            }
            $values = [];
            foreach ($attribute as $index => $item) {
                if ($item instanceof AttributeValueInterface || !is_string($key)) {
                    $values[] = $item;
                } else {
                    if (is_string($key)) {
                        if (isset($oldValues[$index])) {
                            $value = $oldValues[$index];
                            $value->setValue($item);
                        } else {
                            if ($valuePrototype === null) {
                                $valuePrototype = static::getAttributeValueInstance();
                            }
                            if ($attributePrototype === null) {
                                $attributePrototype = static::getAttributeInstanceByName($key);
                            }
                            $value = clone $valuePrototype;
                            $value->setEAttribute($attributePrototype);
                            $value->setValue($item);
                        }
                        $values[] = $value;
                    }
                }
            }
            return $values;
        }
    }

    protected function normalizeEAttributeValue($value)
    {
        if (is_array($value) && ArrayHelper::isAssociative($value, false)) {
            $value = isset($value['value']) ? $value['value'] : null;
        }

        return $value;
    }

    /**
     * @param $name attribute name
     * @return AttributeValueInterface[]
     */
    protected function getEAttributeOldValues($name)
    {
        $values = [];
        foreach ($this->relatedEAttributes as $value) {
            if ($value->getName() === $name) {
                $values[] = $value;
            }
        }

        return $values;
    }
} 