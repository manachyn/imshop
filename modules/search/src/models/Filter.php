<?php

namespace im\search\models;

use im\search\backend\Module;
use yii\db\ActiveRecord;

/**
 * Filters set model class.
 *
 * @property integer $id
 * @property string $name
 */
class Filter extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%filters}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('filter', 'ID'),
            'name' => Module::t('filter', 'Name'),
        ];
    }
}
