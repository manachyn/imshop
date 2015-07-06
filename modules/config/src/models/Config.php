<?php

namespace im\config\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%config}}".
 *
 * @property integer $id
 * @property string $component
 * @property string $key
 * @property string $value
 */
class Config extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%config}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'value'], 'required'],
            ['component', 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'key' => 'Key',
            'value' => 'Value',
        ];
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
//        if (is_array($value) || is_object($value))
//            $value = serialize($value);

        $this->value = serialize($value);
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
//        if (is_array($value) || is_object($value))
//            $value = serialize($value);
        return unserialize($this->value);
    }

}
