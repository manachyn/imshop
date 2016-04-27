<?php

namespace im\eav\components;

use im\eav\Module;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;

class AttributeTypes
{
    const STRING_TYPE = 'string';
    const BOOLEAN_TYPE = 'boolean';
    const ARRAY_TYPE = 'array';
    const SERIALIZED_TYPE = 'serialized';

    const TEXT_INPUT_FIELD = 'textInput';
    const TEXTAREA_FIELD = 'textarea';
    const RADIO_FIELD = 'radio';
    const CHECKBOX_FIELD = 'checkbox';
    const DROP_DOWN_LIST_FIELD = 'dropDownList';
    const LIST_BOX_FIELD = 'listBox';
    const CHECKBOX_LIST_FIELD = 'checkboxList';
    const RADIO_LIST_FIELD = 'radioList';

    const REQUIRED_VALIDATOR = 'required';
    const STRING_VALIDATOR = 'string';
    const EMAIL_VALIDATOR = 'email';
    const DATE_VALIDATOR = 'date';
    const INTEGER_VALIDATOR = 'integer';
    const NUMBER_VALIDATOR = 'number';
    const MATCH_VALIDATOR = 'match';
    const UNIQUE_VALIDATOR = 'unique';

    public static function getTypes()
    {
        return [
            self::STRING_TYPE,
            self::ARRAY_TYPE,
            self::SERIALIZED_TYPE
        ];
    }

    public static function getChoices()
    {
        $types = \Yii::$app->get('typesRegister')->entityTypes;
        array_walk($types, function(&$value, $key){
            $value = Inflector::titleize($key);
        });
        return array_merge(array(
            self::STRING_TYPE => Module::t('attribute', 'Text'),
        ), $types);
    }

    public static function getFieldTypeChoices()
    {
        return [
            self::TEXT_INPUT_FIELD => Module::t('attribute', 'Text input'),
            self::TEXTAREA_FIELD => Module::t('attribute', 'Textarea'),
            self::RADIO_FIELD => Module::t('attribute', 'Radio button'),
            self::CHECKBOX_FIELD => Module::t('attribute', 'Checkbox'),
            self::DROP_DOWN_LIST_FIELD => Module::t('attribute', 'Select')
        ];
    }

    public static function getValidatorChoices()
    {
        return [
            self::REQUIRED_VALIDATOR => Module::t('attribute', 'Required'),
            self::STRING_VALIDATOR => Module::t('attribute', 'String'),
            self::EMAIL_VALIDATOR => Module::t('attribute', 'Email'),
            self::DATE_VALIDATOR => Module::t('attribute', 'Date'),
            self::INTEGER_VALIDATOR => Module::t('attribute', 'Integer'),
            self::NUMBER_VALIDATOR => Module::t('attribute', 'Number'),
            self::MATCH_VALIDATOR => Module::t('attribute', 'Match'),
            self::UNIQUE_VALIDATOR => Module::t('attribute', 'Unique')
        ];
    }

    public static function isActiveRecordType($type)
    {
        $class = \Yii::$app->get('typesRegister')->getEntityClass($type);
        return is_subclass_of($class, ActiveRecord::className());
    }

    public static function getFieldType($type)
    {

    }
}
