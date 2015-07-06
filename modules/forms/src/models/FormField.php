<?php

namespace im\forms\models;

use Yii;

/**
 * This is the model class for table "{{%form_fields}}".
 *
 * @property integer $id
 * @property integer $form_id
 * @property string $name
 * @property string $label
 * @property string $hint
 * @property string $type
 * @property string $options_data
 * @property string $rules_data
 * @property string $items
 *
 * @property Forms $form
 */
class FormField extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%form_fields}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['form_id'], 'integer'],
            [['name', 'label', 'hint', 'type', 'options', 'items'], 'required'],
            [['name', 'label', 'hint', 'type'], 'string', 'max' => 100],
            [['options', 'items'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'form_id' => Yii::t('app', 'Form ID'),
            'name' => Yii::t('app', 'Name'),
            'label' => Yii::t('app', 'Label'),
            'hint' => Yii::t('app', 'Hint'),
            'type' => Yii::t('app', 'Type'),
            'options' => Yii::t('app', 'Options'),
            'items' => Yii::t('app', 'Items'),
        ];
    }

    public function getOptions()
    {
        return unserialize($this->options_data);
    }

    public function setOptions($options)
    {
        return $this->options_data = serialize($options);
    }

    public function getRules()
    {
        $rules = unserialize($this->rules_data);
        array_walk($rules, function(&$rule) { array_unshift($rule, $this->name); });
        return $rules;
    }

    public function setRules($rules)
    {
        return $this->rules_data = serialize($rules);
    }

//    /**
//     * @return \yii\db\ActiveQuery
//     */
//    public function getForm()
//    {
//        return $this->hasOne(Forms::className(), ['id' => 'form_id']);
//    }
}
