<?php

namespace im\variation\models;

use im\base\behaviors\RelationsBehavior;
use im\variation\components\OptionInterface;
use im\variation\components\OptionValueInterface;
use im\variation\Module;
use Yii;
use yii\db\ActiveRecord;

/**
 * Option model class.
 *
 * @property integer $id
 * @property string $name
 * @property string $presentation
 *
 * @property OptionValue[] $relatedOptionValues
 */
class Option extends ActiveRecord implements OptionInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%options}}';
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
            [['name', 'presentation'], 'required'],
            ['name', 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('option', 'ID'),
            'name' => Module::t('option', 'Name'),
            'presentation' => Module::t('option', 'Presentation'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOptionValuesRelation()
    {
        return $this->hasMany(OptionValue::className(), ['option_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getPresentation()
    {
        return $this->presentation;
    }

    /**
     * {@inheritdoc}
     */
    public function setPresentation($presentation)
    {
        $this->presentation = $presentation;
    }

    /**
     * {@inheritdoc}
     */
    public function getValues()
    {
        return $this->relatedOptionValues;
    }

    /**
     * {@inheritdoc}
     */
    public function setValues($optionValues)
    {
        $this->relatedOptionValues = $optionValues;
        foreach ($optionValues as $optionValue) {
            if ($optionValue->getOption() !== $this) {
                $optionValue->setOption($this);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addValue(OptionValueInterface $optionValue)
    {
        if (!$this->hasValue($optionValue)) {
            $this->relatedOptionValues = array_merge($this->relatedOptionValues, [$optionValue]);
            if ($optionValue->getOption() !== $this) {
                $optionValue->setOption($this);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeValue(OptionValueInterface $optionValue)
    {
        if ($this->hasValue($optionValue)) {
            $optionValues = $this->relatedOptionValues;
            /** @var OptionValue $optionValue */
            foreach ($optionValues as $key => $item) {
                if ($item->equals($optionValue)) {
                    unset($optionValues[$key]);
                }
            }
            $this->relatedOptionValues = $optionValues;
            $optionValue->setOption(null);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasValue(OptionValueInterface $optionValue)
    {
        /** @var OptionValue $optionValue */
        foreach ($this->relatedOptionValues as $item) {
            if ($item->equals($optionValue)) {
                return true;
            }
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function equals($record)
    {
        if ($this->isNewRecord && $record->isNewRecord) {
            return $record === $this;
        }

        return parent::equals($record);
    }
}
