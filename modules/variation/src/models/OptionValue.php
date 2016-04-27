<?php

namespace im\variation\models;

use im\base\behaviors\RelationsBehavior;
use im\variation\components\OptionInterface;
use im\variation\components\OptionValueInterface;
use im\variation\Module;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%option_values}}".
 *
 * @property integer $id
 * @property integer $option_id
 * @property string $value
 *
 * @property Option $relatedOption
 */
class OptionValue extends ActiveRecord implements OptionValueInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%option_values}}';
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
            [['value'], 'required'],
            [['option'], '\app\modules\base\validators\RequiredRelationValidator']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('value', 'ID'),
            'option_id' => Module::t('value', 'Option'),
            'value' => Module::t('value', 'Value')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOptionRelation()
    {
        return $this->hasOne(Option::className(), ['id' => 'option_id']);
    }

    /**
     * @inheritdoc
     */
    public function getOption()
    {
        return $this->relatedOption;
    }

    /**
     * @inheritdoc
     */
    public function setOption(OptionInterface $option = null)
    {
        $this->relatedOption = $option;
        if ($option !== null) {
            $option->addValue($this);
        }
    }

    /**
     * @inheritdoc
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @inheritdoc
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        if ($this->relatedOption === null) {
            throw new \BadMethodCallException('The value is not related with option.');
        }

        return $this->relatedOption->getName();
    }

    /**
     * @inheritdoc
     */
    public function getPresentation()
    {
        if ($this->relatedOption === null) {
            throw new \BadMethodCallException('The value is not related with option.');
        }

        return $this->relatedOption->getPresentation();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->value;
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
