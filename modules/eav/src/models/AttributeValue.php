<?php

namespace im\eav\models;

use im\base\behaviors\RelationsBehavior;
use im\eav\components\AttributeInterface;
use im\eav\components\AttributesHolderInterface;
use im\eav\components\AttributeTypes;
use im\eav\components\AttributeValueInterface;
use im\forms\widgets\DynamicActiveField;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveField;
use yii\widgets\ActiveForm;

/**
 * Attribute class
 *
 * @property integer $id
 * @property integer $entity_id
 * @property string $entity_type
 * @property integer $attribute_id
 * @property integer $attribute_name
 * @property integer $attribute_type
 * @property integer $value_id
 * @property string $string_value
 * @property integer $value_entity_id
 *
 * @property ActiveRecord $relatedEntity
 * @property Attribute $relatedEAttribute
 * @property Value $relatedValue
 * @property ActiveRecord $relatedValueEntity
 */
class AttributeValue extends ActiveRecord implements AttributeValueInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%eav_entity_values}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge([
            [['value'], 'safe'],
            [['eAttribute'], 'im\base\validators\RequiredRelationValidator'],
            [['entity'], 'required']
        ], $this->attributeRules());
    }

    public function attributeRules()
    {
        $attribute = $this->getEAttribute();
//        if ($attribute !== null && $attribute->formField !== null && $rules = $attribute->formField->getRules()) {
//            return $rules;
//        }
        if ($attribute !== null && $rules = $attribute->getRules()) {
            $rules = array_map(function($rule) { return ['value', $rule]; }, $rules);
            return $rules;
        }
        return [];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'entity_id' => Yii::t('app', 'Entity ID'),
            'entity_type' => Yii::t('app', 'Entity Type'),
            'attribute_id' => Yii::t('app', 'Attribute ID'),
            'attribute_name' => Yii::t('app', 'Attribute Name'),
            'attribute_type' => Yii::t('app', 'Attribute Type'),
            'string_value' => Yii::t('app', 'Value'),
            'value_entity_id' => Yii::t('app', 'Value'),
            'value' => $this->getPresentation(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [RelationsBehavior::className()];
    }

    /**
     * @param $config
     * @return AttributeValueInterface
     */
    public static function getInstance($config = [])
    {
        return new static($config);
    }

    /**
     * @return ActiveQuery
     */
    public function getEAttributeRelation()
    {
        return $this->hasOne(Attribute::className(), ['id' => 'attribute_id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getEntityRelation()
    {
        if ($this->entity_type) {
            $class = Yii::$app->get('typesRegister')->getEntityClass($this->entity_type);
            return $this->hasOne($class, ['id' => 'entity_id']);
        } else {
            return null;
        }
    }

    /**
     * @return ActiveQuery
     */
    public function getValueEntityRelation()
    {
        if ($type = $this->getType()) {
            $class = Yii::$app->get('typesRegister')->getEntityClass($type);
            return $this->hasOne($class, ['id' => 'value_entity_id']);
        } else {
            return null;
        }
    }

    /**
     * @return ActiveQuery
     */
    public function getValueRelation()
    {
        return $this->hasOne(Value::className(), ['id' => 'value_id']);
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
    public function setEntity(AttributesHolderInterface $entity = null)
    {
        if ($entity !== null) {
            $this->entity_type = Yii::$app->get('typesRegister')->getEntityType();
        }
        $this->relatedEntity = $entity;
        if ($entity !== null) {
            $entity->addEAttribute($this);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getEAttribute()
    {
        return $this->relatedEAttribute;
    }

    /**
     * {@inheritdoc}
     */
    public function setEAttribute(AttributeInterface $attribute)
    {
        $this->attribute_name = $attribute->getName();
        $this->attribute_type = $attribute->isValuesPredefined() ? 'value' : $attribute->getType();
        $this->relatedEAttribute = $attribute;
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        switch ($this->getType()) {
            case AttributeTypes::BOOLEAN_TYPE:
                return (boolean) $this->string_value;
                break;
            case AttributeTypes::ARRAY_TYPE:
                return unserialize($this->string_value);
                break;
            default:
                if ($this->getType() === 'value') {
                    return $this->relatedValue;
                } elseif (in_array($this->getType(), AttributeTypes::getTypes())) {
                    return $this->string_value;
                } else {
                    return $this->relatedValueEntity;
                }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setValue($value)
    {
        switch (gettype($value)) {
            case 'array':
                $this->attribute_type = AttributeTypes::ARRAY_TYPE;
                $this->string_value = serialize($value);
                break;
            case 'object':
                $this->attribute_type = Yii::$app->get('typesRegister')->getEntityType($value);
                $this->relatedValueEntity = $value;
                break;
            default:
                if ($this->getType() === 'value') {
                    $this->value_id = (int) $value;
                } elseif (in_array($this->getType(), AttributeTypes::getTypes())) {
                    $this->string_value = $value;
                } else {
                    $this->value_entity_id = $value;
                }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->attribute_name;
    }

    /**
     * {@inheritdoc}
     */
    public function getPresentation()
    {
        $this->assertAttributeIsSet();

        return $this->getEAttribute()->getPresentation();
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return $this->attribute_type;
    }

    /**
     * {@inheritdoc}
     */
    public function getUnit()
    {
        $this->assertAttributeIsSet();

        return $this->getEAttribute()->getUnit();
    }

    /**
     * @param ActiveForm|array $form
     * @param array $options
     * @return ActiveField
     * @throws \yii\base\InvalidConfigException
     */
    public function getField($form, $options = [])
    {
        $attribute = "[{$this->getName()}]value";
        $config = [];
        if ($form instanceof ActiveForm) {
            $config = $form->fieldConfig;

        } elseif (is_array($form) && isset($form['fieldConfig'])) {
            $config = $form['fieldConfig'];
        }
        if ($config instanceof \Closure) {
            $config = call_user_func($config, $this, $attribute);
        }
        if (!isset($config['class'])) {
            $config['class'] = is_array($form) ? DynamicActiveField::className() : $form->fieldClass;
        }
        $config['template'] = "{label}\n<div class=\"input-group\">\n{input}<span class=\"input-group-btn\"><button type=\"button\" class=\"btn btn-danger\"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></button>\n</span>\n</div>\n{hint}\n{error}";

        $fieldConfig = $this->getEAttribute()->getFieldConfig();
        $fieldOptions = ArrayHelper::getValue($fieldConfig, 'fieldOptions', []);
        $labelOptions = ArrayHelper::getValue($fieldConfig, 'labelOptions', []);

        if (is_array($form)) {
            $options['formConfig'] = $form;
        } else {
            $options['form'] = $form;
        }

        $field = Yii::createObject(ArrayHelper::merge($config, $fieldOptions, $options, [
            'model' => $this,
            'attribute' => $attribute
        ]));

        if ($labelOptions) {
            $field->label(null, $labelOptions);
        } else {
            $field->label($this->getPresentation());
        }

        $fieldType = ArrayHelper::getValue($fieldConfig, 'fieldType', 'textInput');
        $inputOptions = ArrayHelper::getValue($fieldConfig, 'inputOptions', []);

        if ($this->getEAttribute()->isValuesPredefined()) {
            $fieldType = 'dropDownList';
            $inputOptions['items'] = ArrayHelper::map(Value::find()->where(['attribute_id' => $this->getEAttribute()->id])->asArray()->all(), 'id', 'value');
            $inputOptions['options']['prompt'] = '';
        }

        if (method_exists($field, $fieldType)) {
            $parameters = [];
            $class = new \ReflectionClass(get_class($field));
            $reflectionParameters = $class->getMethod($fieldType)->getParameters();
            foreach ($reflectionParameters as $parameter) {
                $name = $parameter->getName();
                $parameters[$name] = ArrayHelper::remove($inputOptions, $name,
                    $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null);
            }
//            if (array_key_exists('items', $parameters) && empty($parameters['items']) && AttributeTypes::isActiveRecordType($this->getType())) {
//                /** @var ActiveRecord $class */
//                $class = Yii::$app->core->getEntityClass($this->getType());
//                $reflector = new \ReflectionClass($class);
//                $typeName = Inflector::titleize($reflector->getShortName());
//                $hasToString = $reflector->hasMethod('__toString');
//                $items = ArrayHelper::map($class::find()->all(), function($row) {
//                    /** @var ActiveRecord $row */
//                    $pk = $row->getPrimaryKey(false);
//                    return is_array($pk) ? json_encode($pk) : $pk;
//                }, function ($row) use ($hasToString, $typeName) {
//                    /** @var ActiveRecord $row */
//                    if ($hasToString) {
//                        return $row->__toString();
//                    } else {
//                        $pk = $row->getPrimaryKey(false);
//                        $pk = is_array($pk) ? json_encode($pk) : $pk;
//                        return $typeName . ' ' . $pk;
//                    }
//                });
//                $parameters['items'] = $items ? $items : [];
//            }
//
//            //TODO multiple, promp option for dropdown

            call_user_func_array(array($field, $fieldType), $parameters);
        }

        return $field;
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

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return is_array($this->getValue()) ? implode(', ', $this->getValue()) : (string) $this->getValue();
    }

    protected function assertAttributeIsSet()
    {
        if (!$this->attribute_id && !$this->relatedEAttribute) {
            throw new \BadMethodCallException('The attribute is undefined.');
        }
    }
}
