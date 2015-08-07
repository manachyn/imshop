<?php

namespace im\eav\models;

use im\eav\Module;
use Yii;
use yii\db\ActiveRecord;

/**
 * Value model.
 *
 * @property integer $id
 * @property integer $attribute_id
 * @property string $value
 * @property string $presentation
 */
class Value extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%eav_values}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['attribute_id', 'value', 'presentation'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('value', 'ID'),
            'attribute_id' => Module::t('value', 'Attribute'),
            'value' => Module::t('value', 'Value'),
            'presentation' => Module::t('attribute', 'Presentation'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEAttribute()
    {
        return $this->hasOne(Attribute::className(), ['id' => 'attribute_id']);
    }
}
