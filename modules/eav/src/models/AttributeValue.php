<?php

namespace im\eav\models;

use im\base\behaviors\RelationsBehavior;
use im\base\interfaces\TypeableEntityInterface;
use im\eav\components\AttributeInterface;
use im\eav\components\AttributesHolderInterface;
use im\eav\components\AttributeTypes;
use im\eav\components\AttributeValueInterface;
use im\forms\widgets\DynamicActiveField;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
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
 * @property string $string_value
 * @property integer $integer_value
 *
 * @property ActiveRecord $relatedEntity
 * @property Attribute $relatedEAttribute
 * @property ActiveRecord $relatedValueEntity
 */
class AttributeValue extends ActiveRecord implements AttributeValueInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%attribute_values}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge([
            [['value'], 'safe'],
            [['eAttribute'], '\app\modules\base\validators\RequiredRelationValidator'],
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
            'product_id' => Yii::t('app', 'Product ID'),
            'attribute_id' => Yii::t('app', 'Attribute ID'),
            'string_value' => Yii::t('app', 'Value'),
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
            $class = Yii::$app->core->getEntityClass($this->entity_type);
            return $this->hasOne($class, ['id' => 'entity_id']);
        }
        else {
            return null;
        }
    }

    /**
     * @return ActiveQuery
     */
    public function getValueEntityRelation()
    {
        if ($type = $this->getType()) {
            $class = Yii::$app->core->getEntityClass($type);
            return $this->hasOne($class, ['id' => 'integer_value']);
        }
        else
            return null;
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
            $this->entity_type = $entity instanceof TypeableEntityInterface ? $entity->getEntityType() : get_class($entity);
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
        $this->attribute_type = $attribute->getType();
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
                if (in_array($this->getType(), AttributeTypes::getTypes())) {
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
                $this->attribute_type = $value instanceof TypeableEntityInterface ? $value->getEntityType() : get_class($value);
                $this->relatedValueEntity = $value;
                break;
            default:
                if (in_array($this->getType(), AttributeTypes::getTypes())) {
                    $this->string_value = $value;
                } else {
                    $this->integer_value = $value;
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
     * @param ActiveForm|array $form
     * @return ActiveField
     */
    public function getField($form)
    {
        if (is_array($form)) {
            return $this->getDynamicField($form);
        }
        $name = $this->getName();
        $fieldConfig = $this->getEAttribute()->getFieldConfig();
        $fieldOptions = ArrayHelper::getValue($fieldConfig, 'fieldOptions', []);
        $fieldOptions['template'] = "{label}\n<div class=\"input-group\">\n{input}<span class=\"input-group-btn\"><button type=\"button\" class=\"btn btn-danger\"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></button>\n</span>\n</div>\n{hint}\n{error}";
        $labelOptions = ArrayHelper::getValue($fieldConfig, 'labelOptions', []);
        $field = $form->field($this, "[$name]value", $fieldOptions);
        if ($labelOptions) {
            $field->label(null, $labelOptions);
        } else {
            $field->label($this->getPresentation());
        }
        $fieldType = ArrayHelper::getValue($fieldConfig, 'fieldType', 'input');
        $inputOptions = ArrayHelper::getValue($fieldConfig, 'inputOptions', []);
        if (method_exists($field, $fieldType)) {
            $parameters = [];
            $class = new \ReflectionClass(get_class($field));
            $reflectionParameters = $class->getMethod($fieldType)->getParameters();
            foreach ($reflectionParameters as $parameter) {
                $name = $parameter->getName();
                $parameters[$name] = ArrayHelper::remove($inputOptions, $name,
                    $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null);
            }
            if (isset($parameters['options'])) {
                $parameters['options'] = $inputOptions;
            }
            if (array_key_exists('items', $parameters) && empty($parameters['items']) && AttributeTypes::isActiveRecordType($this->getType())) {
                /** @var ActiveRecord $class */
                $class = Yii::$app->core->getEntityClass($this->getType());
                $reflector = new \ReflectionClass($class);
                $typeName = Inflector::titleize($reflector->getShortName());
                $hasToString = $reflector->hasMethod('__toString');
                $items = ArrayHelper::map($class::find()->all(), function($row) {
                    /** @var ActiveRecord $row */
                    $pk = $row->getPrimaryKey(false);
                    return is_array($pk) ? json_encode($pk) : $pk;
                }, function ($row) use ($hasToString, $typeName) {
                    /** @var ActiveRecord $row */
                    if ($hasToString) {
                        return $row->__toString();
                    } else {
                        $pk = $row->getPrimaryKey(false);
                        $pk = is_array($pk) ? json_encode($pk) : $pk;
                        return $typeName . ' ' . $pk;
                    }
                });
                $parameters['items'] = $items ? $items : [];
            }

            //TODO multiple, promp option for dropdown

            call_user_func_array(array($field, $fieldType), $parameters);
        }

        return $field;
    }

    public function getDynamicField($formConfig)
    {
        $name = $this->getName();
        $fieldConfig = $this->getEAttribute()->getFieldConfig();
        $fieldOptions = ArrayHelper::getValue($fieldConfig, 'fieldOptions', []);
        $formFieldConfig = ['class' => DynamicActiveField::className()];
        /** @var DynamicActiveField $field */
        $field = Yii::createObject(ArrayHelper::merge($formFieldConfig, $fieldOptions, [
            'model' => $this,
            'attribute' => "[$name]value",
            'formConfig' => $formConfig,
            'template' => "{label}\n<div class=\"input-group\">\n{input}<span class=\"input-group-btn\"><button type=\"button\" class=\"btn btn-danger\" data-action=\"remove\"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></button>\n</span>\n</div>\n{hint}\n{error}"
        ]));

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
