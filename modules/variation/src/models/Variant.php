<?php

namespace im\variation\models;

use im\base\behaviors\RelationsBehavior;
use im\variation\components\OptionValueInterface;
use im\variation\components\VariableInterface;
use im\variation\components\VariantInterface;
use im\variation\Module;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%variants}}".
 *
 * @property integer $id
 * @property integer $entity_id
 * @property string $entity_type
 * @property string $presentation
 * @property integer $status
 * @property boolean $master
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property ActiveRecord $relatedEntity
 * @property OptionValue[] $relatedOptionValues
 */
class Variant extends ActiveRecord implements VariantInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const DEFAULT_STATUS = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%variants}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            RelationsBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['entity_id', 'status', 'created_at', 'updated_at'], 'integer'],
            ['entity_id', 'default', 'value' => null],
            ['entity_type', 'string'],
            ['status', 'default', 'value' => self::DEFAULT_STATUS],
            ['master', 'boolean'],
            ['master', 'default', 'value' => false],
            ['presentation', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('variant', 'ID'),
            'entity_id' => Module::t('variant', 'Entity'),
            'entity_type' => Module::t('variant', 'Entity Type'),
            'status' => Module::t('variant', 'Status'),
            'created_at' => Module::t('variant', 'Created At'),
            'updated_at' => Module::t('variant', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntityRelation()
    {
        if ($this->entity_type) {
            $class = Yii::$app->get('typesRegister')->getEntityClass($this->entity_type);
            return $this->hasOne($class, ['id' => 'entity_id']);
        }
        else {
            return null;
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOptionValuesRelation()
    {
        return $this->hasMany(OptionValue::className(), ['id' => 'option_value_id'])
            ->viaTable('{{%variant_option_values}}', ['variant_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function isMaster()
    {
        return $this->master;
    }

    /**
     * {@inheritdoc}
     */
    public function setMaster($master)
    {
        $this->master = (Boolean) $master;
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
    public function getEntity()
    {
        return $this->relatedEntity;
    }

    /**
     * {@inheritdoc}
     */
    public function setEntity(VariableInterface $entity = null)
    {
        $this->entity_type = $entity ? Yii::$app->get('typesRegister')->getEntityType($entity) : '';
        $this->relatedEntity = $entity;
        if ($entity !== null) {
            $entity->addVariant($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getOptions()
    {
        return $this->relatedOptionValues;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions($options)
    {
        $this->relatedOptionValues = $options;
    }

    /**
     * {@inheritdoc}
     */
    public function addOption(OptionValueInterface $option)
    {
        if (!$this->hasOption($option)) {
            $this->relatedOptionValues = array_merge($this->relatedOptionValues, [$option]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeOption(OptionValueInterface $option)
    {
        if ($this->hasOption($option)) {
            $options = $this->relatedOptionValues;
            foreach ($options as $key => $item) {
                if ($item->equals($option)) {
                    unset($options[$key]);
                }
            }
            $this->relatedOptionValues = $options;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasOption(OptionValueInterface $option)
    {
        /** @var OptionValue $option */
        $options = $this->relatedOptionValues;
        foreach ($options as $item) {
            if ($item->equals($option)) {
                return true;
            }
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaults(VariantInterface $masterVariant)
    {
        // TODO: Implement setDefaults() method.
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
