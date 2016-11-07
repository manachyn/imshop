<?php

namespace im\eav\models;

use im\eav\components\AttributeInterface;
use im\eav\components\AttributeTypes;
use im\eav\Module;
use im\forms\models\FormField;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;

/**
 * Attribute model class.
 *
 * @property integer $id
 * @property string $name
 * @property string $presentation
 * @property string $unit
 * @property string $type
 * @property string $entity_type
 * @property string $field_config_data
 * @property string $rules_config_data
 * @property bool $predefined_values
 *
 * @property FormField $formField
 */
class Attribute extends ActiveRecord implements AttributeInterface
{
    const DEFAULT_TYPE = AttributeTypes::STRING_TYPE;
    const DEFAULT_FIELD = AttributeTypes::TEXT_INPUT_FIELD;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->type = static::DEFAULT_TYPE;
        $filedConfig = $this->getFieldConfig();
        if (!isset($filedConfig['fieldType'])) {
            $filedConfig['fieldType'] = static::DEFAULT_FIELD;
            $this->setFieldConfig($filedConfig);
        }
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%eav_attributes}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'presentation', 'type'], 'required'],
            //['name', 'unique'],
            ['type', 'default', 'value' => self::DEFAULT_TYPE],
            [['unit', 'fieldConfig', 'rulesConfig', 'predefined_values'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('attribute', 'ID'),
            'name' => Module::t('attribute', 'Name'),
            'presentation' => Module::t('attribute', 'Presentation'),
            'type' => Module::t('attribute', 'Type'),
            'fieldConfig' => Module::t('attribute', 'Filed Config'),
            'rulesConfig' => Module::t('attribute', 'Validators'),
            'predefined_values' => Module::t('attribute', 'Predefined values')
        ];
    }

//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getFormField()
//    {
//        return $this->hasOne(FormField::className(), ['id' => 'form_field_id']);
//    }

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
    public function getType()
    {
        return $this->type;
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * {@inheritdoc}
     */
    public function isValuesPredefined()
    {
        return (bool) $this->predefined_values;
    }

    /**
     * {@inheritdoc}
     */
    public function getUnit()
    {
        return $this->unit;
    }

    public function getFieldConfig()
    {
        $config = unserialize($this->field_config_data);
        return $config === false ? [] : $config;
    }

    public function setFieldConfig($config)
    {
        return $this->field_config_data = serialize($config);
    }

    public function getRulesConfig()
    {
        $config = unserialize($this->rules_config_data);
        return $config === false ? [] : $config;
    }

    public function setRulesConfig($config)
    {
        return $this->rules_config_data = serialize($config);
    }

    public function getRules()
    {
        //$rules = array_map(function($rule) { return [$this->getName(), $rule]; }, $this->getRulesConfig());
        return $this->getRulesConfig();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getValuesRelation()
    {
        return $this->hasMany(Value::className(), ['attribute_id' => 'id']);
    }

    /**
     * @return Value[]
     */
    public function getValues()
    {
        return $this->getValuesRelation()->all();
    }

    /**
     * Returns attributes by entity type.
     *
     * @param $entityType
     * @param string|array $orderBy
     * @return static[]
     */
    public static function findByEntityType($entityType, $orderBy = null)
    {
        $query = static::find()->where(['entity_type' => $entityType]);
        if ($orderBy) {
            $query->orderBy($orderBy);
        }

        return $query->all();
    }

    /**
     * Returns attribute by name and entity type.
     *
     * @param string $name
     * @param string $entityType
     * @return null|self
     */
    public static function findByNameAndEntityType($name, $entityType)
    {
        return static::findOne(['entity_type' => $entityType, 'name' => $name]);
    }

    /**
     * @param $name
     * @return AttributeInterface
     */
    public static function getInstanceByName($name)
    {
        $instance = static::findOne(['name' => $name]);
        if ($instance === null) {
            /** @var AttributeInterface $instance */
            $instance = new static;
            $instance->setName($name);
            $instance->setPresentation(Inflector::titleize($name));
        }
        return $instance;
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
